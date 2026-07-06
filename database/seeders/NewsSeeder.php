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
            'title' => '色鉛筆追加！',
            'content' => '<p>32色の色鉛筆を追加しました。新しいカラーバリエーションをお楽しみください。</p>',
        ]);

        News::create([
            'title' => '新商品を追加しました',
            'content' => '<p>新しい商品ラインナップを追加しました。ぜひご覧ください。</p>',
        ]);

        News::create([
            'title' => 'サイトリニューアルのお知らせ',
            'content' => '<p>サイトデザインをリニューアルしました。より使いやすくなっています。</p>',
        ]);
    }
}