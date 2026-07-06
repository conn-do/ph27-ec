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
        News::create([
            'title' => 'サイトメンテナンスのお知らせ',
            'content' => '<p>7月10日10時からメンテナンスを行います。ご迷惑をお掛けします。</p>',
        ]);

        News::create([
            'title' => '夏季休業のお知らせ',
            'content' => '<p>誠に勝手ながら、8月10日から8月15日まで夏季休業とさせていただきます。</p>',
        ]);

        News::create([
            'title' => '新商品入荷のお知らせ',
            'content' => '<p>待望の新商品が入荷いたします。こちらでご確認ください。</p>',
        ]);
    }
}
