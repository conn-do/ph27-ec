<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;

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
        Storage::disk('public')->put('img/products/card-case.png', file_get_contents('public/img/products/card-case.png'));
        Storage::disk('public')->put('img/products/clear-file.png', file_get_contents('public/img/products/clear-file.png'));
        Storage::disk('public')->put('img/products/post-it.png', file_get_contents('public/img/products/post-it.png'));

        $penCategory = Category::where('slug', 'pen')->first();
        $paperCategory = Category::where('slug', 'paper')->first();
        $storageCategory = Category::where('slug', 'storage')->first();

        Product::create([
            'name' => 'えんぴつ',
            'description' => 'かっこいいえんぴつです',
            'price' => 100,
            'image' => 'img/products/pencil.png',
            'stock' => 10,
            'category_id' => $penCategory->id,
        ]);
        Product::create([
            'name' => 'ペン',
            'description' => 'かわいいペンです',
            'price' => 200,
            'image' => 'img/products/pen.png',
            'stock' => 10,
            'category_id' => $penCategory->id,
        ]);
        Product::create([
            'name' => 'ノート',
            'description' => 'きれいなノートです',
            'price' => 300,
            'image' => 'img/products/note.png',
            'stock' => 10,
            'category_id' => $paperCategory->id,
        ]);
        Product::create([
            'name' => '名刺入れ',
            'description' => 'かっこいい名刺入れです',
            'price' => 400,
            'image' => 'img/products/card-case.png',
            'stock' => 10,
            'category_id' => $storageCategory->id,
        ]);
        Product::create([
            'name' => 'クリアファイル',
            'description' => '透明でかっこいいクリアファイルです',
            'price' => 500,
            'image' => 'img/products/clear-file.png',
            'stock' => 10,
            'category_id' => $storageCategory->id,
        ]);
        Product::create([
            'name' => 'ふせん',
            'description' => 'かっこいいふせんです',
            'price' => 600,
            'image' => 'img/products/post-it.png',
            'stock' => 10,
            'category_id' => $paperCategory->id,
        ]);
    }
}
