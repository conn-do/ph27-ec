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
        $p1 = new News();
        $p1->title = 'とてもすごいペンが入荷されました';
        $p1->content = 'とてもすごいペンの在庫が入荷されました。';
        $p1->save();

        $p2 = new News();
        $p2->title = '最大20%OFFセール開催中';
        $p2->content = 'とてもお得なセールが開催中です。';
        $p2->save();

        $p3 = new News();
        $p3->title = '新商品が登場しました';
        $p3->content = '新しい商品が登場しました。ぜひチェックしてください。';
        $p3->save();
    }
}
