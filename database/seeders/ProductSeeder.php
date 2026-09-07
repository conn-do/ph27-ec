<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'なめらかに書ける、日々のペン', 'price' => 680, 'description' => '考えごとを、すっと紙の上へ。毎日のメモや手紙に寄り添う、軽やかな書き心地のボールペンです。持ちやすい軸とシンプルな佇まいで、机の上にも、ペンケースにも。', 'image' => 'images/products/pen.png', 'category' => 'pen', 'stock' => 20],
            ['name' => '余白を楽しむ、ブルーノート', 'price' => 1280, 'description' => 'アイデアも、今日の出来事も。自由に書き留めたくなる、淡いブルーのノートです。落ち着いた色の表紙と心地よい紙の手触りが、書く時間を少し特別にしてくれます。', 'image' => 'images/products/note.png', 'category' => 'notebook', 'stock' => 15],
            ['name' => '木のぬくもり、そのままの鉛筆', 'price' => 220, 'description' => '手になじむ木の感触と、やわらかな筆跡。スケッチや勉強、気軽なメモに使いたい一本です。削るひとときまで楽しめる、毎日の定番をどうぞ。', 'image' => 'images/products/pencil.png', 'category' => 'pen', 'stock' => 30],
        ];
        foreach ($products as $product) {
            if (! Storage::disk('public')->exists($product['image'])) {
                Storage::disk('public')->put($product['image'], file_get_contents(public_path($product['image'])));
            }
            $product['category_id'] = Category::where('slug', $product['category'])->firstOrFail()->id;
            unset($product['category']);
            $record = Product::firstOrCreate(['image' => $product['image']], $product);
            if ($record->category_id === null) {
                $record->update(['category_id' => $product['category_id']]);
            }
        }
    }
}
