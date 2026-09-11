<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'emoji' => fake()->randomElement(['✏️', '🖊️', '📓', '🧽', '🎒', '📐', '🍬']),
            'color' => fake()->randomElement(['red', 'blue', 'green', 'yellow', 'purple']),
            'sort_order' => fake()->numberBetween(1, 99),
        ];
    }
}
