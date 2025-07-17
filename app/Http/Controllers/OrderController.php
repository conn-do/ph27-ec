<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Order;
use App\Models\orderDetails;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // echo '注文しました';
        }
        try {
            DB::beginTransaction();
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

            $stock = $product->stock;
            $stock -= $quantity;
            if ($stock < 0) {
                throw new Exception('在庫がないです');
            }
            $product->stock = $stock;
            $product->save();
        }

        foreach ($items as $item) {
            echo '<br>';
            echo $item['product']->name;
            echo '<br>';
        }

        session()->forget('cart');
        echo "  合計金額: {$totalPrice}<br> ";
        echo '注文しました';
        DB::commit();
    } catch (Exception $e) {
        echo 'エラーが起こりました!<br>';
        echo $e->getMessage();
        DB::rollBack();
    }
}
}
