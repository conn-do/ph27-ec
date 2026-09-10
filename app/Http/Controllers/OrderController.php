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
    public function confirm(Request $request)
    {
        $cart = session()->get('cart', []);

        $items = [];
        $totalPrice = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
            ];

            $totalPrice += $product->price * $quantity;
        }

        $address = $request->user()->address;

        if (!$address) {
            return redirect('/address');
        }

        $shippingFee = 500;

        if (str_contains($address->address, '北海道')) {
            $shippingFee = 1000;
        } elseif (str_contains($address->address, '沖縄')) {
            $shippingFee = 1500;
        }

        $grandTotal = $totalPrice + $shippingFee;

        return view('orders.confirm', [
            'items' => $items,
            'totalPrice' => $totalPrice,
            'address' => $address,
            'shippingFee' => $shippingFee,
            'grandTotal' => $grandTotal,
        ]);
    }

    public function store(Request $request)
    {
        // 例外処理
        // try の中でエラーが起きたら
        // catch の中の処理が実行される
        try {
            // 注文処理
            // [1 => 3, 2 => 5] (商品ID => 数量)
            $cart = session()->get('cart', []);

            if (empty($cart)) {
                return redirect('/cart');
            }

            DB::beginTransaction();

            $totalPrice = 0;

            foreach ($cart as $productId => $quantity) {
                $product = Product::find($productId);
                $totalPrice += $product->price * $quantity;
            }

            $address = $request->user()->address;

            $shippingFee = 500;

            if (str_contains($address->address, '北海道')) {
                $shippingFee = 1000;
            } elseif (str_contains($address->address, '沖縄')) {
                $shippingFee = 1500;
            }

            $grandTotal = $totalPrice + $shippingFee;

            $order = new Order();
            $order->total_price = $grandTotal;
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
}