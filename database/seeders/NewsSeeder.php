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
        $news1->title = 'とてもすごいペンが入荷されました';
        $news1->content = 'とてもすごいペンの在庫が入荷されました。';
        $news1->save();

        $news2 = new News();
        $news2->title = '最大20%OFFセール開催中';
        $news2->content = 'とてもお得なセールが開催中です。';
        $news2->save();

        $news3 = new News();
        $news3->title = '新商品が登場しました';
        $news3->content = '新しい商品が登場しました。ぜひチェックしてください。';
        $news3->save();
    }
}
