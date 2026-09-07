<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            '余白のオンラインショップがオープンしました。' => '書くことから、日々を豊かに。毎日に寄り添う文房具を集めた「余白」がオープンしました。お気に入りの一本、一冊をゆっくりとお選びください。',
            'はじめてのお買い物ガイド' => '商品をカートに入れたら、ログインしてお届け先を入力してください。送料は全国一律500円、商品合計5,000円以上で無料です。表示価格はすべて税込です。',
            'デモショップのご利用について' => 'このサイトはPH27の学習用ECサイトです。注文内容の保存と在庫の更新を体験できますが、実際の決済や配送は行われません。お届け先には架空の情報を入力してください。',
        ] as $title => $content) {
            News::firstOrCreate(['title' => $title], ['content' => $content]);
        }
    }
}
