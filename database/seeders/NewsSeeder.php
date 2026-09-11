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
        $topics = [
            [
                'title' => 'あたらしい ふでばこが とどいたよ',
                'emoji' => '🎒',
                'content' => 'ボタンを おすと ひきだしが とびだす「ロボットふでばこ」が おみせに ならびました。かずが すくないので はやい者がちです。',
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'しょうひぜいの しくみを おぼえよう',
                'emoji' => '🧮',
                'content' => 'ぶんぼうぐは 10%、おかしは 8% の しょうひぜいが かかります。レジの がめんで けいさんの じゅんばんが 見られるので、じぶんでも けいさんして くらべてみてね。',
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'おかしコーナーが できました',
                'emoji' => '🍬',
                'content' => 'ラムネや ガムなどの おかしを はじめました。たべものは しょうひぜいが 8% になるので、ぶんぼうぐと くらべてみると おもしろいよ。',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'おこづかい帳を つかってみよう',
                'emoji' => '👛',
                'content' => 'マイページから おこづかい帳が 見られます。いつ なにに いくら つかったのかを ふりかえると、つぎの かいものが じょうずに なります。',
                'published_at' => now()->subDays(8),
            ],
            [
                'title' => 'かんそうを かいてみよう',
                'emoji' => '💬',
                'content' => 'かったことの ある しょうひんには、ほしの かずと かんそうを かけます。つぎに かうひとの ヒントに なるので、ぜひ かいてね。',
                'published_at' => now()->subDays(12),
            ],
        ];

        foreach ($topics as $topic) {
            News::updateOrCreate(['title' => $topic['title']], $topic);
        }
    }
}
