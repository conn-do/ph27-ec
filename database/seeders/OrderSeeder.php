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
        // 既存データをクリア
        OrderDetail::query()->delete();
        Order::query()->delete();

        $user = User::first();
        $product1 = Product::find(1);
        $product2 = Product::find(2);

        if (!$user || !$product1 || !$product2) {
            return;
        }

        // 商品の単価を取得（セール価格があればそちらを使用）
        $price1 = $product1->is_sale && $product1->sale_price ? $product1->sale_price : $product1->price;
        $price2 = $product2->is_sale && $product2->sale_price ? $product2->sale_price : $product2->price;

        $totalPrice = $price1 + ($price2 * 2);

        $order = new Order();
        $order->user_id = $user->id;
        $order->total_price = $totalPrice;
        $order->save();

        $detail1 = new OrderDetail();
        $detail1->order_id = $order->id;
        $detail1->product_id = $product1->id;
        $detail1->quantity = 1;
        $detail1->price = $price1; // 追加: priceを設定
        $detail1->save();

        $detail2 = new OrderDetail();
        $detail2->order_id = $order->id;
        $detail2->product_id = $product2->id;
        $detail2->quantity = 2;
        $detail2->price = $price2; // 追加: priceを設定
        $detail2->save();
    }
}