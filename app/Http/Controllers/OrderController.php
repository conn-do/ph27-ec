<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function create()
    {
        $cart = session()->get('cart', []);

        // カートが空なら配送先入力に進ませず、カート画面に戻す
        if (empty($cart)) {
            return redirect('/cart')->with('message', 'カートに商品がありません。');
        }

        return view('orders.create');
    }

    public function store(Request $request)
    {
        // 配送先のバリデーション
        $validated = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_postal_code' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
        ]);

        // カートが空のままPOSTされた場合はここで弾く（直接URLアクセス対策）
        if (empty(session()->get('cart', []))) {
            return redirect('/cart')->with('message', 'カートに商品がありません。');
        }

        // 例外処理
        // try の中でエラーが起きたら
        // catch の中の処理が実行される
        try {
            DB::beginTransaction();
            // 注文処理
            // [1 => 3, 2 => 5] (商品ID => 数量)
            $cart = session()->get('cart', []);
            $totalPrice = 0;
            foreach ($cart as $productId => $quantity) {
                $product = Product::find($productId);
                $totalPrice += $product->price * $quantity;
            }

            $order = new Order;
            $order->total_price = $totalPrice;
            $order->user_id = $request->user()->id;
            // バリデーション済みの配送先情報を設定
            $order->shipping_name = $validated['shipping_name'];
            $order->shipping_postal_code = $validated['shipping_postal_code'];
            $order->shipping_address = $validated['shipping_address'];
            $order->shipping_phone = $validated['shipping_phone'];
            $order->save();

            foreach ($cart as $productId => $quantity) {
                $detail = new OrderDetail;
                $detail->order_id = $order->id;
                $detail->product_id = $productId;
                $detail->quantity = $quantity;
                $detail->save();

                /** @var Product $product */
                $product = Product::find($productId);

                if ($quantity > $product->stock) {
                    // 例外を投げる
                    throw new Exception('在庫がありません');
                }

                $product->stock -= $quantity;
                $product->save();
            }

            // トランザクションが正常に終了したら
            // DBの変更を確定する
            DB::commit();

            session()->forget('cart');

            session()->flash('message', '注文が完了しました！');

            return view('orders.complete', [
                'order' => $order,
            ]);
        } catch (Exception $e) {
            // エラー処理
            // DBの変更を元に戻す
            DB::rollBack();
            $message = '申し訳ございません！エラーが発生しました。最初からやり直してください。<br>';
            $message .= $e->getMessage();

            return redirect('/cart')->with('message', $message);
        }
    }

    public function index(Request $request)
    {
        $orders = $request->user()->orders;

        return view('orders.index', [
            'orders' => $orders->sortByDesc('created_at'),
        ]);
    }

    public function show(Order $order)
    {
        return view('orders.show', [
            'order' => $order,
        ]);
    }

    public function cancel(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($order->status !== OrderStatus::Pending) {
            return redirect('/orders/'.$order->id)
                ->with('message', 'この注文はキャンセルできません。');
        }

        $order->update(['status' => OrderStatus::Cancelled]);

        return redirect('/orders/'.$order->id)
            ->with('message', '注文をキャンセルしました。');
    }
}
