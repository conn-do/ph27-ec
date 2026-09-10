<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'category' => 'writing',
                'name' => 'ミニマルシャープペンシル',
                'slug' => 'minimal-mechanical-pencil',
                'description' => 'マットなアルミ軸と細身のグリップが手に馴染む、0.5mm芯のシャープペンシル。考え事やスケッチに静かに寄り添う一本です。',
                'price' => 1320,
                'stock' => 48,
                'is_featured' => true,
            ],
            [
                'category' => 'writing',
                'name' => 'ソフトインクゲルペン',
                'slug' => 'soft-ink-gel-pen',
                'description' => 'やさしいアイボリーの軸に、くっきり書ける黒の0.5mmゲルインクを合わせました。手紙にも仕事のメモにも使いやすいノック式です。',
                'price' => 440,
                'stock' => 96,
                'is_featured' => false,
            ],
            [
                'category' => 'writing',
                'name' => 'シダー鉛筆セット',
                'slug' => 'cedar-graphite-pencil-set',
                'description' => '木肌を生かした無塗装の六角鉛筆を6本セットに。なめらかなHB芯で、余白への書き込みからラフなデッサンまで楽しめます。',
                'price' => 880,
                'stock' => 60,
                'is_featured' => false,
            ],
            [
                'category' => 'notebook',
                'name' => 'グリッドノート A5',
                'slug' => 'grid-note-a5',
                'description' => '淡いグレーの5mm方眼と、目にやさしいクリーム色の紙を使ったA5ノート。糸かがり製本で開きやすく、図も文章も自由に残せます。',
                'price' => 880,
                'stock' => 40,
                'is_featured' => true,
            ],
            [
                'category' => 'notebook',
                'name' => 'ソフトカバージャーナル',
                'slug' => 'soft-cover-journal',
                'description' => '落ち着いたセージ色のソフトカバーに、無地の本文としおり紐を組み合わせた日付なしのジャーナル。今日の小さな発見を書き留めて。',
                'price' => 1540,
                'stock' => 32,
                'is_featured' => true,
            ],
            [
                'category' => 'notebook',
                'name' => 'ポケットメモ 3冊セット',
                'slug' => 'pocket-memo-trio',
                'description' => '砂色、白、チャコールの表紙を揃えたA7メモ帳3冊セット。バッグやポケットに入れて、ひらめきをその場で書き残せます。',
                'price' => 660,
                'stock' => 55,
                'is_featured' => false,
            ],
            [
                'category' => 'desk',
                'name' => 'オークデスクトレイ',
                'slug' => 'oak-desk-tray',
                'description' => 'オーク材の木目を生かした浅型デスクトレイ。ペンや眼鏡、よく使う小物の定位置をつくり、机の上に穏やかな余白を生みます。',
                'price' => 3300,
                'stock' => 18,
                'is_featured' => true,
            ],
            [
                'category' => 'desk',
                'name' => 'コンクリートペンスタンド',
                'slug' => 'concrete-pen-stand',
                'description' => '石のような表情を楽しめる、ライトグレーのコンクリート製ペンスタンド。底面にはデスクを傷つけにくいコルクをあしらいました。',
                'price' => 2420,
                'stock' => 20,
                'is_featured' => false,
            ],
            [
                'category' => 'desk',
                'name' => 'フェルトデスクマット',
                'slug' => 'felt-desk-mat',
                'description' => '柔らかな杢グレーのフェルトでつくった60×30cmのデスクマット。キーボードやノートの下に敷いて、作業スペースを心地よく整えます。',
                'price' => 2860,
                'stock' => 24,
                'is_featured' => false,
            ],
            [
                'category' => 'storage',
                'name' => 'キャンバスツールポーチ',
                'slug' => 'canvas-tool-pouch',
                'description' => '生成りの帆布に真鍮色のファスナーを合わせた文具ポーチ。ペン、定規、付箋をまとめて持ち歩ける、ほどよいマチ付きです。',
                'price' => 1980,
                'stock' => 28,
                'is_featured' => true,
            ],
            [
                'category' => 'storage',
                'name' => 'アコーディオンドキュメントファイル',
                'slug' => 'accordion-document-file',
                'description' => 'A4の書類をテーマごとに仕分けできる、7ポケットの蛇腹式ファイル。落ち着いたサンドベージュとゴム留めのシンプルな佇まいです。',
                'price' => 1650,
                'stock' => 30,
                'is_featured' => false,
            ],
            [
                'category' => 'storage',
                'name' => 'クラフトアーカイブボックス',
                'slug' => 'kraft-archive-box',
                'description' => '使い終えたノートや大切な紙ものを保管する、蓋付きのA4クラフトボックス。背面のラベル欄で中身をすぐに見つけられます。',
                'price' => 1100,
                'stock' => 36,
                'is_featured' => false,
            ],
            [
                'category' => 'tools',
                'name' => '真鍮クリップセット',
                'slug' => 'brass-clip-set',
                'description' => '使うほどに色合いが深まる真鍮製の小さなクリップ4個セット。書類を留めるほか、手帳の目印としても楽しめます。',
                'price' => 770,
                'stock' => 64,
                'is_featured' => true,
            ],
            [
                'category' => 'tools',
                'name' => 'アルミ定規 15cm',
                'slug' => 'aluminium-ruler-15cm',
                'description' => '細い目盛りが読みやすい、軽量な15cmアルミ定規。ヘアライン仕上げのすっきりした形で、小さなペンケースにも収まります。',
                'price' => 990,
                'stock' => 42,
                'is_featured' => false,
            ],
            [
                'category' => 'tools',
                'name' => 'コンパクトペーパーハサミ',
                'slug' => 'compact-paper-scissors',
                'description' => '紙を扱う日常の作業にちょうどよい、小ぶりなステンレスはさみ。丸みのある黒いハンドルと収納用のキャップを備えています。',
                'price' => 1760,
                'stock' => 22,
                'is_featured' => false,
            ],
        ];

        foreach ($products as $product) {
            $category = Category::where('slug', $product['category'])->firstOrFail();
            unset($product['category']);

            $attributes = [
                ...$product,
                'category_id' => $category->id,
                'image' => '/images/products/'.$product['slug'].'.jpg',
                'is_active' => true,
            ];

            $existing = Product::where('slug', $product['slug'])->first();

            if ($existing !== null) {
                $existing->fill([
                    'category_id' => $attributes['category_id'],
                    'name' => $attributes['name'],
                    'description' => $attributes['description'],
                    'image' => $attributes['image'],
                    'is_featured' => $attributes['is_featured'],
                ]);

                if ($existing->isDirty()) {
                    $existing->save();
                }

                continue;
            }

            Product::create($attributes);
        }
    }
}
