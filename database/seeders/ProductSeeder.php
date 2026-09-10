<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::query()->firstOrCreate(
            ['slug' => 'pen'],
            ['name' => '筆記用具'],
        );

        $products = [
            [
                'name' => 'すごいペン',
                'price' => 814,
                'description' => 'とてもすごいペンです。',
                'image' => 'images/products/pen.png',
            ],
            [
                'name' => 'きれいなノート',
                'price' => 600,
                'description' => 'とてもきれいなノートです。',
                'image' => 'images/products/note.png',
            ],
            [
                'name' => 'よく消える鉛筆',
                'price' => 770,
                'description' => 'とてもよく消える鉛筆です。',
                'image' => 'images/products/pencil.png',
            ],
        ];

        foreach ($products as $product) {
            Product::query()->updateOrCreate(
                ['name' => $product['name']],
                $product + ['category_id' => $category->id],
            );
        }
    }
}