<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'なめらかに書ける、日々のペン', 'price' => 680, 'description' => '考えごとを、すっと紙の上へ。毎日のメモや手紙に寄り添う、軽やかな書き心地のボールペンです。持ちやすい軸とシンプルな佇まいで、机の上にも、ペンケースにも。', 'image' => 'images/products/catalog/01-great-pen.webp', 'category' => 'pen', 'stock' => 20, 'legacy_images' => ['images/products/pen.png', 'images/products/catalog/01-great-pen.png'], 'legacy_names' => ['すごいペン']],
            ['name' => '余白を楽しむ、ブルーノート', 'price' => 1280, 'description' => 'アイデアも、今日の出来事も。自由に書き留めたくなる、淡いブルーのノートです。落ち着いた色の表紙と心地よい紙の手触りが、書く時間を少し特別にしてくれます。', 'image' => 'images/products/catalog/02-clean-notebook.webp', 'category' => 'notebook', 'stock' => 15, 'legacy_images' => ['images/products/note.png', 'images/products/catalog/02-clean-notebook.png'], 'legacy_names' => ['きれいなノート']],
            ['name' => '木のぬくもり、そのままの鉛筆', 'price' => 220, 'description' => '手になじむ木の感触と、やわらかな筆跡。スケッチや勉強、気軽なメモに使いたい一本です。削るひとときまで楽しめる、毎日の定番をどうぞ。', 'image' => 'images/products/catalog/03-erasable-pencils.webp', 'category' => 'pen', 'stock' => 30, 'legacy_images' => ['images/products/pencil.png', 'images/products/catalog/03-erasable-pencils.png'], 'legacy_names' => ['よく消える鉛筆']],
            ['name' => '手紙を書きたくなる万年筆', 'price' => 2480, 'description' => '細い文字も穏やかに書ける、はじめての一本に選びやすい万年筆です。深いグリーンの軸が手元を上品に整えます。', 'image' => 'images/products/catalog/04-fountain-pen.webp', 'category' => 'pen', 'stock' => 12],
            ['name' => 'やさしい色のマーカーペン 3本組', 'price' => 920, 'description' => 'ノートの大切な言葉を、淡い色でそっと引き立てます。重ねても文字を読みやすい三色セットです。', 'image' => 'images/products/catalog/05-pastel-markers.webp', 'category' => 'pen', 'stock' => 18],
            ['name' => '芯まで選べる製図シャープペン', 'price' => 1100, 'description' => '細かな図や文字を安定して書ける低重心設計。勉強にもアイデアスケッチにも使いやすい一本です。', 'image' => 'images/products/catalog/06-drafting-pencil.webp', 'category' => 'pen', 'stock' => 16],
            ['name' => '朝の計画を整える方眼ノート', 'price' => 980, 'description' => '予定、図、文章を自由に組み合わせられる5ミリ方眼。180度開き、毎日の記録が続けやすいノートです。', 'image' => 'images/products/catalog/07-grid-notebook.webp', 'category' => 'notebook', 'stock' => 24],
            ['name' => '小さな発見を残すメモパッド', 'price' => 480, 'description' => 'ポケットにも収まる小さなメモパッド。思いついた言葉を逃さず、軽やかに持ち歩けます。', 'image' => 'images/products/catalog/08-memo-pad.webp', 'category' => 'notebook', 'stock' => 35],
            ['name' => '季節を贈るレターセット', 'price' => 760, 'description' => 'やわらかな白の便箋と封筒を揃えたセットです。短い言葉にも、手書きならではの温度を添えます。', 'image' => 'images/products/catalog/09-letter-set.webp', 'category' => 'notebook', 'stock' => 14],
            ['name' => '机に馴染む木製ペントレイ', 'price' => 1680, 'description' => 'よく使う筆記具を静かに受け止める木製トレイ。散らかりやすい机の上に、自然な定位置を作ります。', 'image' => 'images/products/catalog/10-wood-pen-tray.webp', 'category' => 'storage', 'stock' => 9],
            ['name' => '帆布のフラットペンケース', 'price' => 1980, 'description' => '必要な筆記具をすっきり持ち歩ける薄型ケース。丈夫な帆布は使うほど手に馴染みます。', 'image' => 'images/products/catalog/11-canvas-pen-case.webp', 'category' => 'storage', 'stock' => 11],
            ['name' => '読書時間のしおりクリップ', 'price' => 540, 'description' => '読みかけのページと小さなメモを一緒に留められる金属クリップ。手帳の目印にも使えます。', 'image' => 'images/products/catalog/12-bookmark-clip.webp', 'category' => 'storage', 'stock' => 26],
        ];
        foreach ($products as $product) {
            $product['category_id'] = Category::where('slug', $product['category'])->firstOrFail()->id;
            unset($product['category']);
            $legacyImages = $product['legacy_images'] ?? [];
            $legacyNames = $product['legacy_names'] ?? [];
            unset($product['legacy_images']);
            unset($product['legacy_names']);

            $record = $legacyImages !== []
                ? Product::whereIn('image', [...$legacyImages, $product['image']])
                    ->whereIn('name', [...$legacyNames, $product['name']])
                    ->first()
                : Product::where('name', $product['name'])->first();
            $record ??= Product::create($product);

            $updates = [];
            if ($record->image !== $product['image']) {
                $updates['image'] = $product['image'];
            }
            if ($record->category_id === null) {
                $updates['category_id'] = $product['category_id'];
            }
            if ($updates !== []) {
                $record->update($updates);
            }
        }
    }
}
