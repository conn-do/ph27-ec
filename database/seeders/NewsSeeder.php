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
        $news1->title = 'サイトOPEN!';
        $news1->content = 'かっこいいサイトができました！！<br>みんな見てね！';
        $news1->save();

        $news2 = new News();
        $news2->title = '商品が追加されました！';
        $news2->content = 'たくさんの商品が追加されました！<br>みんな見てね！';
        $news2->save();

        $news3 = new News();
        $news3->title = 'News機能を追加しました！';
        $news3->content = 'おもしろいよ！<br>みんな見てね！';
        $news3->save();
    }
}
