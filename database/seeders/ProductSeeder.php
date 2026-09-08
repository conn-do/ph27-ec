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
        foreach (['pen', 'note', 'pencil'] as $image) {
            Storage::disk('public')->put(
                "images/products/{$image}.png",
                file_get_contents(public_path("images/products/{$image}.png")),
            );
        }

        $writing = Category::query()->where('slug', 'writing')->firstOrFail();
        $paper = Category::query()->where('slug', 'paper')->firstOrFail();

        collect([
            [
                'name' => 'すらすらゲルインクペン',
                'price' => 320,
                'description' => '軽い書き心地で、毎日のメモやノート時間を気持ちよくする黒インクのペンです。',
                'image' => 'images/products/pen.png',
                'category_id' => $writing->id,
            ],
            [
                'name' => '方眼リングノート',
                'price' => 480,
                'description' => 'アイデア整理にも勉強にも使いやすい、開きやすい方眼リングノートです。',
                'image' => 'images/products/note.png',
                'category_id' => $paper->id,
            ],
            [
                'name' => 'やわらか芯の鉛筆',
                'price' => 180,
                'description' => 'なめらかな書き味と持ちやすさにこだわった、毎日使いたくなる鉛筆です。',
                'image' => 'images/products/pencil.png',
                'category_id' => $writing->id,
            ],
        ])->each(function (array $product): void {
            Product::query()->firstOrCreate(['name' => $product['name']], [...$product, 'stock' => 10]);
        });
    }
}
