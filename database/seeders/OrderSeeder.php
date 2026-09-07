<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'test@example.com')->firstOrFail();
        if ($user->orders()->exists()) {
            return;
        }
        $product1 = Product::where('name', 'すごいペン')->firstOrFail();
        $product2 = Product::where('name', 'きれいなノート')->firstOrFail();

        $totalPrice = $product1->price + $product2->price * 2;

        $order = new Order;
        $order->user_id = $user->id;
        $order->total_price = $totalPrice;
        $order->save();

        $detail1 = new OrderDetail;
        $detail1->order_id = $order->id;
        $detail1->product_id = $product1->id;
        $detail1->quantity = 1;
        $detail1->save();

        $detail2 = new OrderDetail;
        $detail2->order_id = $order->id;
        $detail2->product_id = $product2->id;
        $detail2->quantity = 2;
        $detail2->save();
    }
}
