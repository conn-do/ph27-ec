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
            
            if (empty($cart)) {
                throw new Exception('カートに商品がありません。');
            }

            // ★【修正ポイント】注文データを作る「前」に、すべての商品の在庫をチェックする
            foreach ($cart as $productId => $quantity) {
                /** @var Product $product */
                $product = Product::find($productId);

                if (!$product) {
                    throw new Exception('商品が見つかりませんでした。');
                }

                if ($quantity > $product->stock) {
                    // 例外を投げる
                    throw new Exception("「{$product->name}」の在庫がありません。（在庫数: {$product->stock}）");
                }
            }

            // 在庫チェックOKなら合計金額を計算
            $totalPrice = 0;
            foreach ($cart as $productId => $quantity) {
                $product = Product::find($productId);
                $totalPrice += $product->price * $quantity;
            }

            $order = new Order();
            $order->total_price = $totalPrice;
            $order->user_id = $request->user()->id;
            $order->save();

            // 注文詳細の保存と、在庫の減算処理
            foreach ($cart as $productId => $quantity) {
                $detail = new OrderDetail();
                $detail->order_id = $order->id;
                $detail->product_id = $productId;
                $detail->quantity = $quantity;
                $detail->save();

                /** @var Product $product */
                $product = Product::find($productId);
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
}