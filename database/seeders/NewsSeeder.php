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
        News::updateOrCreate(
            ['title' => '新商品を入荷しました'],
            ['content' => '<p>人気の文房具に新しいカラーが追加されました。</p>']
        );

        News::updateOrCreate(
            ['title' => '送料無料キャンペーンのお知らせ'],
            ['content' => '<p>期間中は税込3,000円以上のご注文で送料が無料になります。</p>']
        );

        News::updateOrCreate(
            ['title' => 'サイトメンテナンスのお知らせ'],
            ['content' => '<p>7月10日 2:00-4:00 にメンテナンスを実施します。</p>']
        );
    }
}
