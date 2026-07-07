<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        News::create([
            'title' => 'サイトオープンのお知らせ',
            'content' => 'この度、すごい文房具サイトをオープンしました。どうぞよろしくお願いいたします。',
        ]);

        News::create([
            'title' => '新商品入荷のお知らせ',
            'content' => '新しいペン・ノート・鉛筆を入荷しました。ぜひご覧ください。',
        ]);

        News::create([
            'title' => '夏季キャンペーン開催中',
            'content' => '本日より夏季キャンペーンを開催します。対象商品が全品10%オフです。',
        ]);
    }
}
