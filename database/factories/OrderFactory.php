<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(10, 200) * 10;
        $taxTotal = intdiv($subtotal * 10, 100);
        $total = $subtotal + $taxTotal;
        $paid = (int) (ceil($total / 100) * 100);

        return [
            'order_number' => Order::generateOrderNumber(),
            'user_id' => User::factory(),
            'subtotal' => $subtotal,
            'tax_total' => $taxTotal,
            'total_price' => $total,
            'paid_amount' => $paid,
            'change_amount' => $paid - $total,
            'status' => 'paid',
        ];
    }
}
