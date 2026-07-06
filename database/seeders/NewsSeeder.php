<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News; // 追加 : Newsもでる

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        News::create([
            'title' => 'おはようございます',
            'content' => '<p>今日も一日頑張りましょう！</p>',
        ]);

        News::create([
            'title' => 'こんにちは',
            'content' => '<p>午後も引き続き頑張りましょう！</p>',
        ]);

        News::create([
            'title' => 'おやすみなさい',
            'content' => '<p>今日も一日お疲れ様でした。</p>',
        ]);
    }
}