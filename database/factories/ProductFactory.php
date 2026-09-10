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
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => fake()->words(3, true),
            'slug' => fake()->unique()->slug(3),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(300, 5000),
            'stock' => fake()->numberBetween(0, 100),
            'image' => null,
            'is_featured' => false,
            'is_active' => true,
        ];
    }
}
