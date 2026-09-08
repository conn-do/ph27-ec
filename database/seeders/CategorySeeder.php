<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => '筆記用具', 'slug' => 'pen'],
            ['name' => 'ノート・手帳', 'slug' => 'notebook'],
            ['name' => '卓上収納', 'slug' => 'organizer'],
            // Add your other categories here...
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }
    }
}
