<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        News::create([
            'title' => '新商品のご案内',
            'content' => '<p>新しい文房具が入荷しました。ぜひ店頭でご覧ください。</p>',
        ]);

        News::create([
            'title' => 'セール開催中',
            'content' => '<p>一部商品を<strong>20%OFF</strong>で販売しています。</p>',
        ]);

        News::create([
            'title' => 'メンテナンスのお知らせ',
            'content' => '<p>明日 2:00 から 3:00 までサイトを一時停止します。</p>',
        ]);
    }
}
