<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // 既存データをクリア
        Category::query()->delete();

        $categoriesData = [
            '筆記具' => [
                'slug' => 'stationery',
                'subs' => [
                    ['name' => 'ボールペン', 'slug' => 'ballpoint-pens'],
                    ['name' => 'シャープペンシル', 'slug' => 'mechanical-pencils'],
                    ['name' => 'マーカー・蛍光ペン', 'slug' => 'markers'],
                    ['name' => '万年筆', 'slug' => 'fountain-pens'],
                    ['name' => '消しゴム・修正用品', 'slug' => 'erasers'],
                ]
            ],
            '紙製品・ノート' => [
                'slug' => 'paper-products',
                'subs' => [
                    ['name' => 'ノート・メモ帳', 'slug' => 'notebooks'],
                    ['name' => 'ルーズリーフ', 'slug' => 'loose-leaf'],
                    ['name' => '手帳・カレンダー', 'slug' => 'planners'],
                ]
            ],
            '収納・整理用品' => [
                'slug' => 'storage',
                'subs' => [
                    ['name' => 'ペンケース', 'slug' => 'pencil-cases'],
                    ['name' => 'デスク整理用品', 'slug' => 'desk-organizers'],
                    ['name' => 'ファイル・バインダー', 'slug' => 'files'],
                ]
            ],
            '事務用品・道具' => [
                'slug' => 'office-supplies',
                'subs' => [
                    ['name' => 'ハサミ・カッター', 'slug' => 'scissors-cutters'],
                    ['name' => 'テープ・のり', 'slug' => 'tapes-glues'],
                    ['name' => '電卓・計測機器', 'slug' => 'calculators'],
                ]
            ],
            '画材・デザイン用品' => [
                'slug' => 'art-supplies',
                'subs' => [
                    ['name' => '色鉛筆・絵の具', 'slug' => 'colored-pencils'],
                    ['name' => 'スケッチブック', 'slug' => 'sketchbooks'],
                ]
            ],
        ];

        foreach ($categoriesData as $parentName => $data) {
            $parent = Category::create([
                'name' => $parentName,
                'slug' => $data['slug'],
                'parent_id' => null,
            ]);

            foreach ($data['subs'] as $sub) {
                Category::create([
                    'name' => $sub['name'],
                    'slug' => $sub['slug'],
                    'parent_id' => $parent->id,
                ]);
            }
        }
    }
}