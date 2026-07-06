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
        'title' => 'ニュース1',
        'content' => 'ニュース1の内容です。',
    ]);

    News::create([
        'title' => 'ニュース2',
        'content' => 'ニュース2の内容です。',
    ]);

    News::create([
        'title' => 'ニュース3',
        'content' => 'ニュース3の内容です。',
    ]);

    News::create([
        'title' => 'ニュース4',
        'content' => 'ニュース4の内容です。',
    ]);
    }
}
