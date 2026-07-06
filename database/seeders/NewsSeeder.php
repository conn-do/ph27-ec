<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $news1 = new News();
        $news1->title = '新商品入荷のお知らせ';
        $news1->content = '新商品入荷のお知らせです。';
        $news1->save();

        $news2 = new News();
        $news2->title = 'セール開催のお知らせ';
        $news2->content = 'セール開催のお知らせです。';
        $news2->save();

        $news3 = new News();
        $news3->title = 'サイトメンテナンスのお知らせ';
        $news3->content = 'サイトメンテナンスのお知らせです。';
        $news3->save();
    }
}
