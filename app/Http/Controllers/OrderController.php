<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        try {
            DB::beginTransaction();
            $order = new Order;
            $order->user_id = $request->user()->id;
            $order->total_price = $totalPrice;
            $order->save();

            foreach ($cart as $productId => $quantity) {
                $orderDetail = new OrderDetail;
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $productId;
                $orderDetail->quantity = $quantity;
                $orderDetail->save();

                /** @var Product $product */
                $product = Product::find($productId);
                $product->stock -= $quantity;
                $product->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $message = '申し訳ございません！エラーが発生しました。最初からやり直してください。<br>';
            $message .= $e->getMessage();
            return redirect()->route('cart.index')->with('message', $message);
        }

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
