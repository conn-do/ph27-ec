<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        $totalPrice = 0;
        foreach ($cart as $productId => $quantity) {
            /** @disregard */
            $product = Product::find($productId);
            $totalPrice += $product->price * $quantity;
        }
        $order = new Order;
        $order->user_id = $request->user()->id;
        $order->total_price = $totalPrice;
        $order->save();

        session()->forget('cart');

        return redirect()->route('orders.complete')->with('message', '注文が完了しました');
    }

    public function complete()
    {
        return view('orders.complete');
    }
}
