<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
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

            $order = new Order();
            $order->total_price = $totalPrice;
            $order->user_id = $request->user()->id;
            $order->save();

            foreach ($cart as $productId => $quantity) {
                $detail = new OrderDetail();
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
        // 自分以外の注文はキャンセルできないようにする
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($order->is_canceled) {
            return redirect('/orders')->with('message', 'この注文は既にキャンセル済みです。');
        }

        try {
            DB::beginTransaction();

            // 注文で減っていた在庫を元に戻す
            foreach ($order->details as $detail) {
                $product = Product::find($detail->product_id);
                $product->stock += $detail->quantity;
                $product->save();
            }

            $order->is_canceled = true;
            $order->save();

            DB::commit();

            return redirect('/orders')->with('message', '注文をキャンセルしました。');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect('/orders')->with('message', 'キャンセルに失敗しました。');
        }
    }
}
