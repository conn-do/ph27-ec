<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Product> */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return ['name' => fake()->words(3, true), 'price' => 500, 'description' => fake()->sentence(), 'stock' => 10, 'image' => 'images/products/note.png', 'category_id' => Category::factory()];
    }
}
