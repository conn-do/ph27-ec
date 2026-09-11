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
        $categories = [
            ['name' => 'えんぴつ', 'slug' => 'pencil', 'emoji' => '✏️', 'color' => 'blue'],
            ['name' => 'ペン', 'slug' => 'pen', 'emoji' => '🖊️', 'color' => 'red'],
            ['name' => 'ノート', 'slug' => 'note', 'emoji' => '📓', 'color' => 'yellow'],
            ['name' => 'けしごむ', 'slug' => 'eraser', 'emoji' => '🧽', 'color' => 'green'],
            ['name' => 'ふでばこ', 'slug' => 'case', 'emoji' => '🎒', 'color' => 'purple'],
            ['name' => 'どうぐ', 'slug' => 'tool', 'emoji' => '📐', 'color' => 'cyan'],
            ['name' => 'おかし', 'slug' => 'snack', 'emoji' => '🍬', 'color' => 'pink'],
        ];

        foreach ($categories as $index => $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                [...$category, 'sort_order' => $index + 1],
            );
        }
    }
}
