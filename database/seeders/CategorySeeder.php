<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['name' => '筆記用具', 'slug' => 'writing'],
            ['name' => 'ノート・紙', 'slug' => 'paper'],
            ['name' => '整理・収納', 'slug' => 'storage'],
            ['name' => 'デスクまわり', 'slug' => 'desk'],
        ])->each(function (array $category): void {
            if ($category['name'] === '筆記用具') {
                Category::query()->firstOrCreate(['name' => $category['name']], $category);

                return;
            }

            Category::query()->updateOrCreate(['slug' => $category['slug']], $category);
        });
    }
}
