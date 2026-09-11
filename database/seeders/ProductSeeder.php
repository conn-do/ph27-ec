<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * しょうひんの もとデータ。
     *
     * 画像は public/images/products/ に おいたものを
     * storage/app/public/ に コピーして つかう。
     *
     * @var list<array{category: string, name: string, price: int, stock: int, tax_rate: int, image: string, description: string}>
     */
    private const PRODUCTS = [
        // えんぴつ
        [
            'category' => 'pencil', 'name' => 'ドラゴンえんぴつ', 'price' => 120, 'stock' => 30,
            'tax_rate' => 10, 'image' => 'pencil.png',
            'description' => 'ドラゴンの もようが かっこいい えんぴつ。にぎると するどい かきごこち。',
        ],
        [
            'category' => 'pencil', 'name' => 'ロケットえんぴつ', 'price' => 100, 'stock' => 25,
            'tax_rate' => 10, 'image' => 'pencil2.png',
            'description' => 'ロケットが うちゅうへ とんでいく デザイン。しんが おれにくいよ。',
        ],

        // ペン
        [
            'category' => 'pen', 'name' => 'ネオンボールペン', 'price' => 250, 'stock' => 20,
            'tax_rate' => 10, 'image' => 'pen.png',
            'description' => 'くらいところで ひかる ネオンカラー。ノートが いっきに はなやかに。',
        ],
        [
            'category' => 'pen', 'name' => 'キラキラゲルペン', 'price' => 300, 'stock' => 15,
            'tax_rate' => 10, 'image' => 'pen2.png',
            'description' => 'ラメが はいった インクで、かいた もじが キラキラ ひかるよ。',
        ],
        [
            'category' => 'pen', 'name' => 'にじいろマーカー', 'price' => 480, 'stock' => 12,
            'tax_rate' => 10, 'image' => 'marker.png',
            'description' => '1ぽんで 7しょくの にじいろが かける ふしぎな マーカー。',
        ],

        // ノート
        [
            'category' => 'note', 'name' => 'ほしぞらノート', 'price' => 200, 'stock' => 40,
            'tax_rate' => 10, 'image' => 'note.png',
            'description' => 'ひょうしが ほしぞらの もよう。ひらくたびに わくわくする ノート。',
        ],
        [
            'category' => 'note', 'name' => 'ほうがんノート', 'price' => 150, 'stock' => 35,
            'tax_rate' => 10, 'image' => 'note2.png',
            'description' => 'ますめが あるから、さんすうの けいさんが きれいに かけるよ。',
        ],

        // けしごむ
        [
            'category' => 'eraser', 'name' => 'ブロックけしゴム', 'price' => 80, 'stock' => 50,
            'tax_rate' => 10, 'image' => 'eraser.png',
            'description' => 'ブロックみたいに つみかさねられる けしゴム。よく けえるよ。',
        ],
        [
            'category' => 'eraser', 'name' => 'モンスターけしゴム', 'price' => 120, 'stock' => 3,
            'tax_rate' => 10, 'image' => 'eraser2.png',
            'description' => 'モンスターの かたちを した けしゴム。あつめると たのしい。',
        ],

        // ふでばこ
        [
            'category' => 'case', 'name' => 'ロボットふでばこ', 'price' => 1200, 'stock' => 8,
            'tax_rate' => 10, 'image' => 'case.png',
            'description' => 'ボタンを おすと ひきだしが とびだす ロボットがたの ふでばこ。',
        ],
        [
            'category' => 'case', 'name' => 'うちゅうふでばこ', 'price' => 980, 'stock' => 0,
            'tax_rate' => 10, 'image' => 'case2.png',
            'description' => 'わくせいの もようが ひろがる ふでばこ。いまは にんきで うりきれちゅう。',
        ],

        // どうぐ
        [
            'category' => 'tool', 'name' => 'とうめいじょうぎ', 'price' => 150, 'stock' => 30,
            'tax_rate' => 10, 'image' => 'ruler.png',
            'description' => 'したの もじが よく見える とうめいな じょうぎ。15センチまで はかれる。',
        ],
        [
            'category' => 'tool', 'name' => 'あんぜんはさみ', 'price' => 380, 'stock' => 18,
            'tax_rate' => 10, 'image' => 'scissors.png',
            'description' => 'さきが まるくて あんぜんな はさみ。かみが サクサク きれるよ。',
        ],
        [
            'category' => 'tool', 'name' => 'くるまえんぴつけずり', 'price' => 600, 'stock' => 10,
            'tax_rate' => 10, 'image' => 'sharpener.png',
            'description' => 'くるまの かたちを した えんぴつけずり。ハンドルを まわして けずろう。',
        ],
        [
            'category' => 'tool', 'name' => 'スティックのり', 'price' => 130, 'stock' => 45,
            'tax_rate' => 10, 'image' => 'glue.png',
            'description' => 'てが よごれない スティックタイプ。ぬったところが 青いから わかりやすい。',
        ],

        // おかし（けいげん税率 8%）
        [
            'category' => 'snack', 'name' => 'ラムネ', 'price' => 30, 'stock' => 100,
            'tax_rate' => 8, 'image' => 'ramune.png',
            'description' => 'シュワっと とける ラムネ。べんきょうの きゅうけいに ぴったり。',
        ],
        [
            'category' => 'snack', 'name' => 'ふうせんガム', 'price' => 20, 'stock' => 100,
            'tax_rate' => 8, 'image' => 'gum.png',
            'description' => '大きな ふうせんが つくれる ガム。あじは いちご。',
        ],
        [
            'category' => 'snack', 'name' => 'チョコレート', 'price' => 50, 'stock' => 80,
            'tax_rate' => 8, 'image' => 'choco.png',
            'description' => 'ひとくちサイズの チョコレート。えんぴつの かたちを しているよ。',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::pluck('id', 'slug');

        foreach (self::PRODUCTS as $row) {
            $path = 'images/products/'.$row['image'];

            $this->copyImageToStorage($path);

            $product = Product::updateOrCreate(
                ['name' => $row['name']],
                [
                    'category_id' => $categories[$row['category']] ?? null,
                    'price' => $row['price'],
                    'stock' => $row['stock'],
                    'tax_rate' => $row['tax_rate'],
                    'description' => $row['description'],
                    'image' => $path,
                    'is_published' => true,
                ],
            );

            if ($product->wasRecentlyCreated && $row['stock'] > 0) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'quantity_change' => $row['stock'],
                    'stock_after' => $row['stock'],
                    'reason' => StockMovement::REASON_RESTOCK,
                ]);
            }
        }
    }

    /**
     * public/ に おいてある 画像を storage に コピーする。
     * まだ 画像を おいていない ばあいは とばす。
     */
    private function copyImageToStorage(string $path): void
    {
        $source = public_path($path);

        if (! File::exists($source)) {
            $this->command?->warn("画像が まだ ありません: public/{$path}");

            return;
        }

        Storage::disk('public')->put($path, File::get($source));
    }
}
