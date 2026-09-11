<?php

namespace Database\Factories;

use App\Enums\CouponType;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->bothify('????##')),
            'type' => CouponType::Fixed,
            'value' => 500,
            'usage_limit' => null,
            'used_count' => 0,
            'expires_at' => null,
            'active' => true,
        ];
    }

    public function percentage(int $value = 10): static
    {
        return $this->state([
            'type' => CouponType::Percentage,
            'value' => $value,
        ]);
    }

    public function expired(): static
    {
        return $this->state([
            'expires_at' => now()->subDay(),
        ]);
    }

    public function inactive(): static
    {
        return $this->state([
            'active' => false,
        ]);
    }

    public function usageLimitReached(): static
    {
        return $this->state(fn (array $attributes) => [
            'usage_limit' => 1,
            'used_count' => 1,
        ]);
    }
}
