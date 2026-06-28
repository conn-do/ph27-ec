<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => '筆記用具',
            'slug' => 'pen',
        ]);
        Category::create([
            'name' => '紙製品',
            'slug' => 'paper',
        ]);
        Category::create([
            'name' => '収納',
            'slug' => 'storage',
        ]);
    }
}
