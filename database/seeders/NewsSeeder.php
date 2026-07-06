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
        News::create([
            'title' => '新商品「すごいペン」を入荷しました',
            'content' => '<p>書き心地にこだわった新商品「すごいペン」を入荷しました。</p><p>毎日の勉強や仕事にぜひご利用ください。</p>',
        ]);

        News::create([
            'title' => 'ノートまとめ買いキャンペーン',
            'content' => '<p>きれいなノートをまとめて購入すると、次回使えるクーポンをプレゼントします。</p>',
        ]);

        News::create([
            'title' => '配送スケジュールのお知らせ',
            'content' => '<p>連休期間中は通常より発送に時間がかかる場合があります。</p><p>余裕を持ったご注文をお願いいたします。</p>',
        ]);

        News::create([
            'title' => '鉛筆フェア開催中',
            'content' => '<p><strong>よく消える鉛筆</strong>を期間限定価格で販売しています。</p><p>在庫がなくなり次第終了です。</p>',
        ]);
    }
}
