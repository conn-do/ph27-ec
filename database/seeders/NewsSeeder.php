<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        News::create([
            'title' => 'ホームページを公開しました',
            'content' => '<p>本日、公式ホームページを公開しました。</p>',
        ]);

        News::create([
            'title' => '新商品のお知らせ',
            'content' => '<p>新しい商品を追加しました。ぜひご覧ください。</p>',
        ]);

        News::create([
            'title' => '営業時間変更のお知らせ',
            'content' => '<p>来週より営業時間が変更になります。</p>',
        ]);
    }
}