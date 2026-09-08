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
        try {
            $cart = session()->get('cart', []);

            if (empty($cart)) {
                throw new Exception('カートに商品が入っていません。');
            }

            foreach ($cart as $productId => $quantity) {
                /** @var Product $product */
                $product = Product::find($productId);
                if (!$product || $quantity > $product->stock) {
                    throw new Exception(($product->name ?? '商品') . 'の在庫が足りません。');
                }
            }

            DB::beginTransaction();

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
                $product->stock -= $quantity;
                $product->save();
            }

            DB::commit();

            session()->forget('cart');
            session()->flash('message', '注文が完了しました！');

            return view('orders.complete', [
                'order' => $order,
            ]);

        } catch (Exception $e) {
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