<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<News>
 */
class NewsFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'emoji' => fake()->randomElement(['📢', '🎉', '✨', '🎁']),
            'content' => fake()->paragraph(),
            'published_at' => now(),
        ];
    }

    /**
     * まだ こうかいして いない おしらせ。
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => ['published_at' => null]);
    }
}
