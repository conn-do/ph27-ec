<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        News::updateOrCreate(
            ['title' => '新商品のお知らせ'],
            ['content' => '新しい文房具が入荷しました！ぜひご覧ください。'],
        );

        News::updateOrCreate(
            ['title' => 'セール開催中'],
            ['content' => '一部商品が20%オフで購入できます。'],
        );

        News::updateOrCreate(
            ['title' => 'メンテナンスのお知らせ'],
            ['content' => '明日2:00〜4:00の間サイトが一時停止します。'],
        );
    }
}
