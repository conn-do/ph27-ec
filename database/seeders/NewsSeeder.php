<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $news1 = News::firstOrNew(['title' => '新商品のお知らせ']);
        $news1->title = '新商品のお知らせ';
        $news1->content = '新しい文房具が入荷しました！ぜひご覧ください。';
        $news1->save();

        $news2 = News::firstOrNew(['title' => '文房具の選び方']);
        $news2->title = '文房具の選び方';
        $news2->content = '書き心地や使う場面に合わせて、お気に入りの文房具を選んでみましょう。';
        $news2->save();

        $news3 = News::firstOrNew(['title' => 'オンラインストアについて']);
        $news3->title = 'オンラインストアについて';
        $news3->content = 'このサイトは授業の学習用です。実際の決済や配送は行いません。';
        $news3->save();
    }
}
