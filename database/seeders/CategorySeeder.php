<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['pen' => '筆記用具', 'notebook' => 'ノート・紙もの', 'storage' => '収納・小物'] as $slug => $name) {
            Category::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }
    }
}
