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
        $category1 = new Category();
        $category1->name = '筆記具';
        $category1->slug = 'writing';
        $category1->save();

        $category2 = new Category();
        $category2->name = 'ノート・手帳';
        $category2->slug = 'notebook';
        $category2->save();

        $category3 = new Category();
        $category3->name = 'スタンプ';
        $category3->slug = 'rubber-stamps';
        $category3->save();

        $category4 = new Category();
        $category4->name = 'クリップ・ピン';
        $category4->slug = 'clips-pins';
        $category4->save();
    }
}