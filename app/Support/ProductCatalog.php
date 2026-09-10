<?php

namespace App\Support;

use Illuminate\Support\Collection;

class ProductCatalog
{
    /**
     * @return Collection<int, array{key: string, name: string, description: string, price: int, image: string, accent: string}>
     */
    public function all(): Collection
    {
        return collect([
            [
                'key' => 'pencil',
                'name' => '黒金HB鉛筆',
                'description' => '重厚なブラック軸と金色の金具が映える、書き心地の良い鉛筆です。',
                'price' => 770,
                'image' => '/images/products/pencil.png',
                'accent' => 'ブラック',
            ],
            [
                'key' => 'pen',
                'name' => 'ハート柄ボールペン',
                'description' => '淡いピンクと透明パーツで、ノート時間が楽しくなるボールペンです。',
                'price' => 814,
                'image' => '/images/products/pen.png',
                'accent' => 'ピンク',
            ],
            [
                'key' => 'note',
                'name' => 'ライトブルーノート',
                'description' => '上品な金箔ロゴ入り。日々の授業メモにも使いやすいノートです。',
                'price' => 600,
                'image' => '/images/products/note.png',
                'accent' => 'ブルー',
            ],
        ]);
    }

    /**
     * @return array{key: string, name: string, description: string, price: int, image: string, accent: string}|null
     */
    public function find(string $key): ?array
    {
        return $this->all()->firstWhere('key', $key);
    }
}
