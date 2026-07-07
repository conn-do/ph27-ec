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
        $p1 = new News();
        $p1->title = 'しっくりこないニュース';
        $p1->content = '【マリオ氏無免許が発覚】サーキットから公道に着地したところをそのまま逮捕';
        $p1->save();

        $p2 = new News();
        $p2->title = '株と為替のトピック';
        $p2->content = '【日経平均70,000円大台突破】イランとアメリカの停戦協議の結果を受け、半導体関連銘柄を中心に値上がり';
        $p2->save();

        $p3 = new News();
        $p3->title = '世界のニュース';
        $p3->content = '【ソ連の台頭「鉄のカーテン」チャーチル氏】1946年3月、アメリカを遊説中のチャーチル元英国首相がミズーリ州で行われた集会にて、ソ連がシュッテティンからトリエステのラインに社会主義の「鉄のカーテン」を敷こうとしていると批判を展開';
        $p3->save();
    }
}
