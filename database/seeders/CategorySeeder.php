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
            [
                'name' => 'Writing',
                'slug' => 'writing',
                'description' => '手に馴染む一本で、日々の言葉を心地よく綴るための筆記具。',
            ],
            [
                'name' => 'Notebook',
                'slug' => 'notebook',
                'description' => '思いつきも、大切な記録も。書く時間が楽しみになるノートと手帳。',
            ],
            [
                'name' => 'Desk',
                'slug' => 'desk',
                'description' => '素材の温もりと使いやすさで、机の上を自分らしく整える道具。',
            ],
            [
                'name' => 'Storage',
                'slug' => 'storage',
                'description' => 'お気に入りの文具や紙ものを、すっきりと大切にしまう収納用品。',
            ],
            [
                'name' => 'Tools',
                'slug' => 'tools',
                'description' => '切る、測る、留める。毎日の小さな作業を気持ちよくする道具。',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
