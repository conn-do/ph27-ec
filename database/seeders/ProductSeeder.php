<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // =========================================================
        // ① 画像のコピー処理
        // =========================================================
        Storage::disk('public')->put(
            'images/products/pen.png',
            file_get_contents(public_path('images/products/pen.png'))
        );
        Storage::disk('public')->put(
            'images/products/note.png',
            file_get_contents(public_path('images/products/note.png'))
        );
        Storage::disk('public')->put(
            'images/products/pencil.png',
            file_get_contents(public_path('images/products/pencil.png'))
        );

        // 追加した画像（拡張子はすべて .jpg）
        if (file_exists(public_path('images/products/marker.jpg'))) {
            Storage::disk('public')->put(
                'images/products/marker.jpg',
                file_get_contents(public_path('images/products/marker.jpg'))
            );
        }
        if (file_exists(public_path('images/products/pencase.jpg'))) {
            Storage::disk('public')->put(
                'images/products/pencase.jpg',
                file_get_contents(public_path('images/products/pencase.jpg'))
            );
        }
        if (file_exists(public_path('images/products/organizer.jpg'))) {
            Storage::disk('public')->put(
                'images/products/organizer.jpg',
                file_get_contents(public_path('images/products/organizer.jpg'))
            );
        }
        if (file_exists(public_path('images/products/case.jpg'))) {
            Storage::disk('public')->put(
                'images/products/case.jpg',
                file_get_contents(public_path('images/products/case.jpg'))
            );
        }

        // =========================================================
        // カテゴリの準備
        // =========================================================
        $penCategory = Category::firstOrCreate(
            ['slug' => 'pen'],
            ['name' => '筆記具・文房具']
        );

        $storageCategory = Category::firstOrCreate(
            ['slug' => 'storage'],
            ['name' => '収納']
        );

        // =========================================================
        // 商品登録
        // =========================================================
        $p1 = new Product();
        $p1->name = 'すごいペン';
        $p1->price = fake()->numberBetween(100, 500);
        $p1->description = 'とてもすごいペンです。書き味となめらかさが抜群です。';
        $p1->stock = 20;
        $p1->image = 'images/products/pen.png';
        $p1->category_id = $penCategory->id;
        $p1->save();

        $p2 = new Product();
        $p2->name = 'きれいなノート';
        $p2->price = fake()->numberBetween(200, 800);
        $p2->description = 'とてもきれいなノートです。裏抜けしにくい上質紙を使用しています。';
        $p2->stock = 15;
        $p2->image = 'images/products/note.png';
        $p2->category_id = $penCategory->id;
        $p2->save();

        $p3 = new Product();
        $p3->name = 'よく消える鉛筆';
        $p3->price = fake()->numberBetween(100, 400);
        $p3->description = 'とてもよく消える鉛筆です。濃く書けてきれいに消せます。';
        $p3->stock = 30;
        $p3->image = 'images/products/pencil.png';
        $p3->category_id = $penCategory->id;
        $p3->save();

        $p4 = new Product();
        $p4->name = 'カラフルマーカー 5色セット';
        $p4->price = fake()->numberBetween(500, 1200);
        $p4->description = '鮮やかな発色の5色マーカーセットです。ノート整理や勉強に最適です。';
        $p4->stock = 10;
        $p4->image = 'images/products/marker.jpg';
        $p4->category_id = $penCategory->id;
        $p4->save();

        $p5 = new Product();
        $p5->name = '自立する大容量ペンケース';
        $p5->price = fake()->numberBetween(1200, 2500);
        $p5->description = 'デスク上で自立する便利なペンケースです。たくさんの文房具をスッキリ収納できます。';
        $p5->stock = 12;
        $p5->image = 'images/products/pencase.jpg';
        $p5->category_id = $storageCategory->id;
        $p5->save();

        $p6 = new Product();
        $p6->name = '透明デスクオーガナイザー';
        $p6->price = fake()->numberBetween(1500, 3000);
        $p6->description = '中身が見やすいクリア素材のデスク整理ラックです。ペンや小物を綺麗に分類できます。';
        $p6->stock = 8;
        $p6->image = 'images/products/organizer.jpg';
        $p6->category_id = $storageCategory->id;
        $p6->save();

        $p7 = new Product();
        $p7->name = '持ち運び書類キャリングケース';
        $p7->price = fake()->numberBetween(800, 1800);
        $p7->description = 'A4サイズの書類やノートをまとめて持ち運べる丈夫なキャリングケースです。';
        $p7->stock = 5;
        $p7->image = 'images/products/case.jpg';
        $p7->category_id = $storageCategory->id;
        $p7->save();
    }
}