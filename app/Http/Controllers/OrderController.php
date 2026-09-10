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
        try {
            DB::beginTransaction();

            // カート取得
            // [商品ID => 数量]
            // 例：[1 => 3, 2 => 5]
            $cart = session()->get('cart', []);

            $totalPrice = 0;

            // 合計金額を計算
            foreach ($cart as $productId => $quantity) {
                $product = Product::find($productId);

                if (!$product) {
                    throw new Exception('商品が見つかりません');
                }

                // 在庫チェック
                if ($quantity > $product->stock) {
                    throw new Exception(
                        $product->name . 'の在庫がありません'
                    );
                }

                $totalPrice += $product->price * $quantity;
            }

            // 注文を作成
            $order = new Order();
            $order->total_price = $totalPrice;
            $order->user_id = $request->user()->id;
            $order->save();

            // 注文明細を作成
            foreach ($cart as $productId => $quantity) {

                $detail = new OrderDetail();

                $detail->order_id = $order->id;
                $detail->product_id = $productId;
                $detail->quantity = $quantity;

                $detail->save();

                // 商品取得
                /** @var Product $product */
                $product = Product::find($productId);

                // 在庫を減らす
                $product->stock -= $quantity;

                // 売上数を増やす
                $product->sales_count += $quantity;

                // 保存
                $product->save();
            }

            // トランザクション確定
            DB::commit();

            // カートを空にする
            session()->forget('cart');

            // 完了メッセージ
            session()->flash(
                'message',
                '注文が完了しました！'
            );

            return view('orders.complete', [
                'order' => $order,
            ]);

        } catch (Exception $e) {

            // DBの変更を元に戻す
            DB::rollBack();

            $message =
                '申し訳ございません！エラーが発生しました。'
                . '最初からやり直してください。<br>';

            $message .= $e->getMessage();

            return redirect('/cart')
                ->with('message', $message);
        }
    }

    // 注文履歴
    public function index(Request $request)
    {
        $orders = $request->user()->orders;

        return view('orders.index', [
            'orders' => $orders->sortByDesc('created_at'),
        ]);
    }

    // 注文詳細
    public function show(Order $order)
    {
        return view('orders.show', [
            'order' => $order,
        ]);
    }
}