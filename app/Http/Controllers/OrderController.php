<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Order;
use App\Models\orderDetails;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function order(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect('/products');
        }

        $items = [];
        $totalPrice = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            $totalPrice += $product->price * $quantity;
            $items [] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
        echo '注文しました';
        }

        $order = new Order();
        $order->user_id = $request->user()->id;
        $order->total_price = $totalPrice;
        $order->save();

        foreach ($items as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'];

            $orderDetails = new orderDetails();
            $orderDetails->order_id = $order->id;
            $orderDetails->product_id = $product->id;
            $orderDetails->price = $product->id;
            $orderDetails->qunantity = $quantity;
            $orderDetails->save();


        }

        foreach ($items as $item) {
            echo $item['product']->name;
            echo '<br>';
        }

        session()->forget('cart');
        echo "  合計金額 {$totalPrice}<br> ";
        echo '注文しました';
    }
}
