<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

class OrderController extends Controller
{
    public function store()
    {
        // [1 => 3, 2 => 5] (商品ID => 数量)
        $cart = session()->get('cart', []);
        $totalPrice = 0;
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            $totalPrice += $product->price * $quantity;
        }

        $order = new Order();
        $order->total_price = $totalPrice;
        $order->save();

        session()->forget('cart');

        session()->flash('message', '注文が完了しました！');

        return view('orders.complete', [
            'order' => $order,
        ]);
    }
}
