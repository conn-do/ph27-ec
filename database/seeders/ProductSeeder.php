<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'えんぴつ',
            'description' => 'かっこいいえんぴつです',
            'price' => 100,
            'image' => 'img/products/pencil.png',
        ]);
        Product::create([
            'name' => 'ペン',
            'description' => 'かわいいペンです',
            'price' => 200,
            'image' => 'img/products/pen.png',
        ]);
        Product::create([
            'name' => 'ノート',
            'description' => 'きれいなノートです',
            'price' => 300,
            'image' => 'img/products/note.png',
        ]);
    }
}
