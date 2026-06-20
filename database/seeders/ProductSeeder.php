<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Storage::disk('public')->put('img/products/pencil.png', file_get_contents('public/img/products/pencil.png'));
        Storage::disk('public')->put('img/products/pen.png', file_get_contents('public/img/products/pen.png'));
        Storage::disk('public')->put('img/products/note.png', file_get_contents('public/img/products/note.png'));
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
