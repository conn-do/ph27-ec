<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::factory();
        $quantity = fake()->numberBetween(1, 3);

        return [
            'order_id' => Order::factory(),
            'product_id' => $product,
            'product_name' => fake()->words(2, true),
            'unit_price' => $unitPrice = fake()->numberBetween(1, 20) * 10,
            'tax_rate' => 10,
            'quantity' => $quantity,
            'subtotal' => $subtotal = $unitPrice * $quantity,
            'tax_amount' => intdiv($subtotal * 10, 100),
        ];
    }

    /**
     * じっさいの しょうひんから 明細を つくる。
     */
    public function forProduct(Product $product, int $quantity = 1): static
    {
        return $this->state(fn (array $attributes) => [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'unit_price' => $product->price,
            'tax_rate' => $product->tax_rate,
            'quantity' => $quantity,
            'subtotal' => $subtotal = $product->price * $quantity,
            'tax_amount' => intdiv($subtotal * $product->tax_rate, 100),
        ]);
    }
}
