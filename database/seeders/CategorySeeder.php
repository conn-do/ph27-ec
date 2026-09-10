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
        $category1 = Category::firstOrNew(['slug' => 'pen']);
        $category1->name = '筆記用具';
        $category1->slug = 'pen';
        $category1->save();

        $category2 = Category::firstOrNew(['slug' => 'storage']);
        $category2->name = '収納';
        $category2->slug = 'storage';
        $category2->save();
        Category::firstOrCreate(['slug' => 'notebook'], ['name' => 'ノート・紙製品']);
    }
}
