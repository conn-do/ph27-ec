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

    public function index(Request $request)
    {
        // $orders = Order::where('user_id', $request->user()->id)->get();
        $orders = $request->user()->orders;
        return view('orders.index', [
            'orders' => $orders,
        ]);
    }
}
