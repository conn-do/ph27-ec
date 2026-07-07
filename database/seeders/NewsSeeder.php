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
        'title' => '1件目のお知らせ',
        'content' => '<p>テストです</p>',
    ]);

    News::create([
        'title' => '2件目のお知らせ',
        'content' => '<p>テスト2</p>',
    ]);

    News::create([
        'title' => '3件目のお知らせ',
        'content' => '<p>テスト3</p>',
    ]);
}
}
