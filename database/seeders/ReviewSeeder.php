<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereIn('email', ['hikaru@example.com', 'nanami@example.com'])->get();

        if ($users->isEmpty()) {
            return;
        }

        $reviews = [
            'ドラゴンえんぴつ' => [[5, 'もようが かっこよくて、かくのが たのしくなった！'], [4, 'しんが おれにくいです。']],
            'ほしぞらノート' => [[5, 'ひょうしが きれい。ノートを ひらくのが すきに なりました。']],
            'ブロックけしゴム' => [[4, 'よく けえる。つみかさねて あそべるのも たのしい。']],
            'にじいろマーカー' => [[5, '1ぽんで いろが かわるのが すごい！']],
            'ラムネ' => [[5, 'べんきょうの あとに たべると げんきが でる。']],
            'あんぜんはさみ' => [[4, 'てが いたく ならない。こうさくが すすみました。']],
        ];

        foreach ($reviews as $productName => $rows) {
            $product = Product::where('name', $productName)->first();

            if ($product === null) {
                continue;
            }

            foreach ($rows as $index => [$rating, $comment]) {
                $user = $users[$index] ?? $users->first();

                Review::updateOrCreate(
                    ['user_id' => $user->id, 'product_id' => $product->id],
                    ['rating' => $rating, 'comment' => $comment],
                );
            }
        }
    }
}
