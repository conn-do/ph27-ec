<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function order(Request $request)
    {
        DB::beginTransaction();

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect('/products');
        }

        $products = [];
        $totalPrice = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
            $totalPrice += $product->price * $quantity;
        }

        try {
            $order = new Order();
            $order->user_id = $request->user()->id;
            $order->total_price = $totalPrice;
            $order->save();

            foreach ($items as $item) {
                $product = $item['product'];
                $quantity = $item['quantity'];
                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $product->id;
                $orderDetail->quantity = $quantity;
                $orderDetail->price = $product->price;
                $orderDetail->save();

                $stock = $product->stock - $quantity;
                $product->stock = $stock;
                if ($stock < 0) {
                    throw new \Exception('在庫が不足しています');
                }
                $product->save();
            }

            session()->forget('cart');

            echo "合計金額: " . $totalPrice;
            echo '注文しました';
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect('/cart')->with('error', '注文に失敗しました: ' . $e->getMessage());
        }
    }
}
