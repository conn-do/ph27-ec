<?php

namespace App\Services;

use App\Exceptions\OrderPlacementException;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderPlacementService
{
    /**
     * @param  array{customer_name: string, postal_code: string, address: string}  $customer
     */
    public function place(User $user, array $quantities, array $customer): Order
    {
        if ($quantities === []) {
            throw new OrderPlacementException('カートに商品がありません。');
        }

        return DB::transaction(function () use ($user, $quantities, $customer): Order {
            $products = Product::query()
                ->whereIn('id', array_keys($quantities))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $total = 0;

            foreach ($quantities as $productId => $quantity) {
                $product = $products->get($productId);

                if (! $product instanceof Product) {
                    throw new OrderPlacementException('商品情報を確認できません。カートを確認してください。');
                }

                if (! $product->is_active) {
                    throw new OrderPlacementException('販売を終了した商品が含まれています。カートを確認してください。');
                }

                if ($product->stock < $quantity) {
                    throw new OrderPlacementException('在庫が不足している商品があります。カートを確認してください。');
                }

                $total += $product->price * $quantity;
            }

            $order = $user->orders()->create([
                ...$customer,
                'total_price' => $total,
                'status' => 'pending',
            ]);

            foreach ($quantities as $productId => $quantity) {
                /** @var Product $product */
                $product = $products->get($productId);

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);

                $product->decrement('stock', $quantity);
            }

            return $order;
        }, 3);
    }
}
