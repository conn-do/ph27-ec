<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect('/cart')->withErrors([
                'cart' => 'カートに商品がありません。',
            ]);
        }

        try {
            $order = DB::transaction(function () use ($cart, $request) {
                $products = Product::whereIn('id', array_keys($cart))
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $totalPrice = 0;

                foreach ($cart as $productId => $quantity) {
                    $product = $products->get((int) $productId);

                    if (! $product) {
                        throw new \Exception('商品が見つかりません。');
                    }

                    if ($quantity > $product->stock) {
                        throw new \Exception($product->name.'の在庫が不足しています。');
                    }

                    $totalPrice += $product->price * $quantity;
                }

                $order = new Order;
                $order->total_price = $totalPrice;
                $order->user_id = $request->user()->id;
                $order->save();

                foreach ($cart as $productId => $quantity) {
                    $product = $products->get((int) $productId);

                    $detail = new OrderDetail;
                    $detail->order_id = $order->id;
                    $detail->product_id = $product->id;
                    $detail->quantity = $quantity;
                    $detail->save();

                    $product->stock -= $quantity;
                    $product->save();
                }

                return $order;
            });

            session()->forget('cart');
            session()->flash('message', '注文が完了しました！');

            return view('orders.complete', [
                'order' => $order,
            ]);
        } catch (Throwable $e) {
            return redirect('/cart')->withErrors([
                'cart' => '申し訳ございません。'.$e->getMessage(),
            ]);
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
        $order->load('details.product');

        return view('orders.show', [
            'order' => $order,
        ]);
    }
}
