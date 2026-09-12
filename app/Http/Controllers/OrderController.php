<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\PointHistory;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // 1. チェックアウト画面表示
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        $items = [];
        $totalPrice = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $items[] = [
                    'product'  => $product,
                    'quantity' => $quantity,
                ];
                
                $price = ($product->is_sale && $product->sale_price) ? $product->sale_price : $product->price;
                $totalPrice += $price * $quantity;
            }
        }

        if (empty($items)) {
            return redirect('/cart')->with('message', 'カートに商品が入っていません。');
        }

        return view('checkout', compact('items', 'totalPrice'));
    }

    // 2. 注文確定処理（ポイント利用＆自動還元ポイント付与）
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        $user = $request->user();

        if (empty($cart)) {
            return redirect('/cart')->with('message', 'カートに商品が入っていません。');
        }

        // 送信されたポイントを取得
        $usePoint = (int) $request->input('use_point', 0);

        // 注文合計金額の事前面出
        $totalPrice = 0;
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $price = ($product->is_sale && $product->sale_price) ? $product->sale_price : $product->price;
                $totalPrice += $price * $quantity;
            }
        }

        if ($request->input('gift_option') === 'box') {
            $totalPrice += 300;
        }

        // ポイント利用上限（所持ポイント または 注文合計金額 の小さい方）
        $maxUsePoint = min($user->point ?? 0, $totalPrice);

        // バリデーション
        $request->validate([
            'use_point' => 'nullable|integer|min:0|max:' . $maxUsePoint,
            'gift_option' => 'nullable|string',
            'payment_method' => 'nullable|string',
        ], [
            'use_point.max' => 'ご利用ポイントは注文合計金額（または所持ポイント）を超えることはできません。',
        ]);

        if ($usePoint > ($user->point ?? 0)) {
            return back()->with('error', '所持ポイントを超えて利用することはできません。');
        }

        if ($usePoint > $totalPrice) {
            return back()->with('error', '商品合計金額を超えるポイントは利用できません。');
        }

        // 🎁 ポイント割引適用後の最終金額
        $finalTotalPrice = max(0, $totalPrice - $usePoint);

        // 🎁 購入還元ポイントの計算（20円 ＝ 1pt 還元）
        $earnedPoint = (int) floor($finalTotalPrice / 20);

        $order = null;

        DB::transaction(function () use ($request, $user, $cart, $finalTotalPrice, $usePoint, $earnedPoint, &$order) {
            $order = new Order();
            $order->user_id = $user->id;
            $order->total_price = $finalTotalPrice;
            $order->used_point = $usePoint;
            $order->gift_option = $request->input('gift_option', 'none');
            $order->status = 'ordered';
            $order->save();

            foreach ($cart as $productId => $quantity) {
                $product = Product::find($productId);
                if ($product) {
                    $price = ($product->is_sale && $product->sale_price) ? $product->sale_price : $product->price;

                    $detail = new OrderDetail();
                    $detail->order_id = $order->id;
                    $detail->product_id = $productId;
                    $detail->quantity = $quantity;
                    $detail->price = $price;
                    $detail->save();

                    $product->stock -= $quantity;
                    $product->save();
                }
            }

            // 🎁 ポイント利用処理＆履歴登録（$usePoint > 0 のとき確実に実行）
            if ($usePoint > 0) {
                // ユーザーのポイントを減算
                $user->decrement('point', $usePoint);

                // 履歴作成
                PointHistory::create([
                    'user_id' => $user->id,
                    'points' => -$usePoint,
                    'description' => "注文 #{$order->id} でのポイント利用",
                ]);
            }

            // 🎁 購入還元ポイント付与（20円＝1pt）
            if ($earnedPoint > 0) {
                $user->increment('point', $earnedPoint);

                PointHistory::create([
                    'user_id' => $user->id,
                    'points' => $earnedPoint,
                    'description' => "注文 #{$order->id} の購入特典ポイント付与（20円=1pt）",
                ]);
            }
        });

        session()->forget('cart');

        return redirect()->route('orders.complete')->with('order_id', $order->id);
    }

    public function complete(Request $request)
    {
        $orderId = session('order_id');
        $order = $orderId ? Order::find($orderId) : null;

        return view('orders.complete', [
            'order' => $order,
        ]);
    }

    public function index(Request $request)
    {
        $query = $request->user()->orders();
        $period = $request->input('period');

        if ($period === '3months') {
            $query->where('created_at', '>=', now()->subMonths(3));
        } elseif ($period === '6months') {
            $query->where('created_at', '>=', now()->subMonths(6));
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        return view('orders.index', [
            'orders' => $orders,
            'period' => $period,
        ]);
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('orders.show', [
            'order' => $order,
        ]);
    }

    public function tracking(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('orders.tracking', [
            'order' => $order,
        ]);
    }

    public function adminIndex()
    {
        $orders = Order::with(['user', 'details.product'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:ordered,shipping_preparing,shipped,delivered',
        ]);

        $order->status = $request->input('status');
        $order->save();

        return back()->with('message', '配送ステータスを更新しました。');
    }

    public function destroy(Order $order)
    {
        $order->details()->delete();
        $order->delete();

        return back()->with('message', '注文データを削除しました。');
    }
}