<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => fake()->words(2, true),
            'price' => fake()->numberBetween(1, 20) * 10,
            'stock' => fake()->numberBetween(5, 50),
            'tax_rate' => 10,
            'description' => fake()->sentence(),
            'image' => 'images/products/pen.png',
            'is_published' => true,
        ];
    }

    /**
     * うりきれの しょうひん。
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => ['stock' => 0]);
    }

    /**
     * けいげん税率（8%）の しょうひん。
     */
    public function reducedTax(): static
    {
        return $this->state(fn (array $attributes) => ['tax_rate' => 8]);
    }

    /**
     * おみせに出していない しょうひん。
     */
    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => ['is_published' => false]);
    }
}
