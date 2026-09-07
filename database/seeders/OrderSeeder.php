<?php

namespace Database\Seeders;

use App\Models\Order;
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
        $user = User::query()->where('email', 'test@example.com')->firstOrFail();
        $pen = Product::query()->where('name', 'すらすらゲルインクペン')->firstOrFail();
        $notebook = Product::query()->where('name', '方眼リングノート')->firstOrFail();

        $order = Order::query()->firstOrCreate(
            ['user_id' => $user->id],
            ['total_price' => $pen->price + ($notebook->price * 2)],
        );

        if ($order->details()->exists()) {
            return;
        }

        $order->details()->createMany([
            ['product_id' => $pen->id, 'quantity' => 1],
            ['product_id' => $notebook->id, 'quantity' => 2],
        ]);
    }
}
