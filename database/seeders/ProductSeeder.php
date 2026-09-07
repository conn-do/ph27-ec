<?php

namespace Database\Seeders;

use App\Models\Category;
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
        foreach (['pen.png', 'note.png', 'pencil.png'] as $fileName) {
            Storage::disk('public')->put(
                'images/products/'.$fileName,
                file_get_contents(public_path('images/products/'.$fileName)),
            );
        }

        $category = Category::where('slug', 'pen')->firstOrFail();

        Product::updateOrCreate(
            ['name' => 'すごいペン'],
            [
                'price' => 300,
                'description' => 'とてもすごいペンです。',
                'image' => 'images/products/pen.png',
                'category_id' => $category->id,
                'stock' => 10,
            ],
        );

        Product::updateOrCreate(
            ['name' => 'きれいなノート'],
            [
                'price' => 450,
                'description' => 'とてもきれいなノートです。',
                'image' => 'images/products/note.png',
                'category_id' => $category->id,
                'stock' => 8,
            ],
        );

        Product::updateOrCreate(
            ['name' => 'よく消える鉛筆'],
            [
                'price' => 120,
                'description' => 'とてもよく消える鉛筆です。',
                'image' => 'images/products/pencil.png',
                'category_id' => $category->id,
                'stock' => 15,
            ],
        );
    }
}
