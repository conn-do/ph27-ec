<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $news1 = new News();
        $news1->title = '新商品のお知らせ';
        $news1->content = '新しい文房具が入荷しました！ぜひご覧ください。';
        $news1->save();

        $news2 = new News();
        $news2->title = 'セール開催中';
        $news2->content = '一部商品が20%オフで購入できます。';
        $news2->save();

        $news3 = new News();
        $news3->title = 'メンテナンスのお知らせ';
        $news3->content = '明日2:00〜4:00の間サイトが一時停止します。';
        $news3->save();
    }
}