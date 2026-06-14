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
        $user = User::first();
        $p1 = Product::find(1);
        $p2 = Product::find(2);

        $order = new Order();
        $order->user_id = $user->id;
        $order->total_price = $p1->price * 2 + $p2->price;
        $order->save();

        $orderDetail1 = new OrderDetail();
        $orderDetail1->order_id = $order->id;
        $orderDetail1->product_id = $p1->id;
        $orderDetail1->quantity = 2;
        $orderDetail1->save();

        $orderDetail2 = new OrderDetail();
        $orderDetail2->order_id = $order->id;
        $orderDetail2->product_id = $p2->id;
        $orderDetail2->quantity = 1;
        $orderDetail2->save();
    }
}
