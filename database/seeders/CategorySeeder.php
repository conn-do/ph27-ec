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
        Category::updateOrCreate(
            ['slug' => 'pen'],
            ['name' => '筆記用具'],
        );

        Category::updateOrCreate(
            ['slug' => 'storage'],
            ['name' => '収納'],
        );
    }
}
