<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'total_price' => fake()->numberBetween(1000, 10000),
            'user_id' => User::factory(),
            'status' => OrderStatus::Pending,
            'shipping_name' => fake()->name(),
            'shipping_postal_code' => fake()->postcode(),
            'shipping_address' => fake()->address(),
            'shipping_phone' => fake()->phoneNumber(),
        ];
    }
}
