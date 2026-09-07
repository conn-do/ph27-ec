<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
<<<<<<< Updated upstream
use App\Models\Category;
=======
use Illuminate\Database\Seeder;
>>>>>>> Stashed changes
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

<<<<<<< Updated upstream
        $category = Category::where('slug', 'pen')->first();

        $p1 = new Product();
        $p1->name = 'すごいペン';
        $p1->price = fake()->randomNumber(3);
        $p1->description = 'とてもすごいペンです。';
        $p1->image = 'images/products/pen.png';
        $p1->category_id = $category->id;
        $p1->save();

        $p2 = new Product();
        $p2->name = 'きれいなノート';
        $p2->price = fake()->randomNumber(3);
        $p2->description = 'とてもきれいなノートです。';
        $p2->image = 'images/products/note.png';
        $p2->category_id = $category->id;
        $p2->save();

        $p3 = new Product();
        $p3->name = 'よく消える鉛筆';
        $p3->price = fake()->randomNumber(3);
        $p3->description = 'とてもよく消える鉛筆です。';
        $p3->image = 'images/products/pencil.png';
        $p3->category_id = $category->id;
        $p3->save();
=======
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
            Product::query()->updateOrCreate(['name' => $product['name']], $product);
        });
>>>>>>> Stashed changes
    }
}
