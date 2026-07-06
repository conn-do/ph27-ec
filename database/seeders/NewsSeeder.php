<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newsItems = [
            [
                'title' => '新商品入荷のお知らせ',
                'content' => '<p>便利な文房具の新商品が入荷しました。</p>',
            ],
            [
                'title' => 'セール開催のお知らせ',
                'content' => '<p>期間限定で一部商品をお得に購入できます。</p>',
            ],
            [
                'title' => 'サイトメンテナンスのお知らせ',
                'content' => '<p>サイトメンテナンスのため、一時的に利用できない時間があります。</p>',
            ],
        ];

        foreach ($newsItems as $newsItem) {
            News::updateOrCreate(
                ['title' => $newsItem['title']],
                ['content' => $newsItem['content']]
            );
        }
    }
}
