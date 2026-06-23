<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $product1 = Product::find(1);
        $product2 = Product::find(2);

        $totalPrice = $product1->price + $product2->price * 2;

        $order = new Order();
        $order->user_id = $user->id;
        $order->total_price = $totalPrice;
        $order->save();

        $detail1 = new OrderDetail();
        $detail1->order_id = $order->id;
        $detail1->product_id = $product1->id;
        $detail1->quantity = 1;
        $detail1->save();

        $detail2 = new OrderDetail();
        $detail2->order_id = $order->id;
        $detail2->product_id = $product2->id;
        $detail2->quantity = 2;
        $detail2->save();
    }
}
