<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class AssignProductsToWritingCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = Category::query()->firstOrCreate(
            ['name' => '筆記用具'],
            ['slug' => 'pen'],
        );

        Product::query()->update(['category_id' => $category->id]);
    }
}
