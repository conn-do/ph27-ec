<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $daikon = Product::create([
            'name' => '大根',
            'price' => 100,
            'stock' => 100,
            'image' => 'product_images/daikon.png',
            // 'description' => '大根の説明',
        ]);
        $daikon->save();

        $ninjin = Product::create([
            'name' => 'ニンジン',
            'price' => 80,
            'stock' => 100,
            'image' => 'product_images/ninjin.png',
            // 'description' => 'ニンジンの説明',
        ]);
        $ninjin->save();

        $kabocha = Product::create([
            'name' => 'カボチャ',
            'price' => 120,
            'stock' => 100,
            'image' => 'product_images/kabocha.png',
            // 'description' => 'カボチャの説明',
        ]);
        $kabocha->save();
    }
}
