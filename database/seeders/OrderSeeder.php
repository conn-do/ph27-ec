<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'test@example.com')->first();
        $product = Product::first();
        if (! $user || ! $product || $user->orders()->exists()) {
            return;
        }
        $order = $user->orders()->create(['total_price' => $product->price, 'shipping_fee' => 0]);
        $order->details()->create(['product_id' => $product->id, 'product_name' => $product->name, 'unit_price' => $product->price, 'quantity' => 1]);
    }
}
