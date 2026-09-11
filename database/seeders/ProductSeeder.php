<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Storage::disk('public')->put(
            'images/products/fountain-pen.webp',
            file_get_contents('public/images/products/fountain-pen.webp')
        );

        Storage::disk('public')->put(
            'images/products/pencil.webp',
            file_get_contents('public/images/products/pencil.webp')
        );

        Storage::disk('public')->put(
            'images/products/sign-pen.webp',
            file_get_contents('public/images/products/sign-pen.webp')
        );

        Storage::disk('public')->put(
            'images/products/brass-ballpoint.webp',
            file_get_contents('public/images/products/brass-ballpoint.webp')
        );

        Storage::disk('public')->put(
            'images/products/typing-paper.webp',
            file_get_contents('public/images/products/typing-paper.webp')
        );

        Storage::disk('public')->put(
            'images/products/schedule-sticky-notes.webp',
            file_get_contents('public/images/products/schedule-sticky-notes.webp')
        );

        Storage::disk('public')->put(
            'images/products/jumbo-ring-sketch-book.webp',
            file_get_contents('public/images/products/jumbo-ring-sketch-book.webp')
        );

        Storage::disk('public')->put(
            'images/products/custard-slice-notebook.webp',
            file_get_contents('public/images/products/custard-slice-notebook.webp')
        );

        Storage::disk('public')->put(
            'images/products/block-shape-stamp.webp',
            file_get_contents('public/images/products/block-shape-stamp.webp')
        );

        Storage::disk('public')->put(
            'images/products/alphabet-stamp.webp',
            file_get_contents('public/images/products/alphabet-stamp.webp')
        );

        Storage::disk('public')->put(
            'images/products/plane-geometry-rubber-stamps.webp',
            file_get_contents('public/images/products/plane-geometry-rubber-stamps.webp')
        );

        Storage::disk('public')->put(
            'images/products/perpetual-calendar-stamp.webp',
            file_get_contents('public/images/products/perpetual-calendar-stamp.webp')
        );

        Storage::disk('public')->put(
            'images/products/butterfly-clips.webp',
            file_get_contents('public/images/products/butterfly-clips.webp')
        );

        Storage::disk('public')->put(
            'images/products/card-clips.webp',
            file_get_contents('public/images/products/card-clips.webp')
        );

        Storage::disk('public')->put(
            'images/products/corner-clips.webp',
            file_get_contents('public/images/products/corner-clips.webp')
        );

        Storage::disk('public')->put(
            'images/products/xl-wood-desk-peg.webp',
            file_get_contents('public/images/products/xl-wood-desk-peg.webp')
        );

        $writing = Category::where('slug', 'writing')->first();
        $notebook = Category::where('slug', 'notebook')->first();
        $rubberStamps = Category::where('slug', 'rubber-stamps')->first();
        $clipsPins = Category::where('slug', 'clips-pins')->first();

        // 筆記具

        $p1 = new Product();
        $p1->name = 'FOUNTAIN PEN';
        $p1->price = 3300;
        $p1->description = '滑らかな書き心地を楽しめる万年筆。落ち着いた筆記の時間を楽しめる、長く使いたいペンです。';
        $p1->image = 'images/products/fountain-pen.webp';
        $p1->category_id = $writing->id;
        $p1->is_active = true;
        $p1->save();

        $p2 = new Product();
        $p2->name = 'GRAPHITE PENCIL';
        $p2->price = 660;
        $p2->description = '木の自然な質感を残した、シンプルな鉛筆。軽やかな書き心地で、日々の筆記に馴染む筆記具です。';
        $p2->image = 'images/products/pencil.webp';
        $p2->category_id = $writing->id;
        $p2->is_active = true;
        $p2->save();

        $p3 = new Product();
        $p3->name = 'PENTEL SIGN PEN';
        $p3->price = 220;
        $p3->description = 'グラフィックドローイングにも適したサインペン。水性・非永久インクを使用し、約1mmの線幅で描けます。10色から選べます。';
        $p3->image = 'images/products/sign-pen.webp';
        $p3->category_id = $writing->id;
        $p3->is_active = true;
        $p3->save();

        $p4 = new Product();
        $p4->name = 'MIDORI BRASS BALLPOINT PEN';
        $p4->price = 1980;
        $p4->description = '真鍮の上品なペンホルダーに、木製のボールペンを組み合わせた一本。日本製で、長く使い続けられるよう仕上げられています。';
        $p4->image = 'images/products/brass-ballpoint.webp';
        $p4->category_id = $writing->id;
        $p4->is_active = true;
        $p4->save();

        // ノート・手帳

        $p5 = new Product();
        $p5->name = 'TYPING PAPER';
        $p5->price = 1980;
        $p5->description = 'タイプライターでの使用を想定した、1980年代のヴィンテージペーパー。しっかりとした紙質で、文字を美しく残せる紙製品です。';
        $p5->image = 'images/products/typing-paper.webp';
        $p5->category_id = $notebook->id;
        $p5->is_active = true;
        $p5->save();

        $p6 = new Product();
        $p6->name = 'SCHEDULE STICKY NOTES';
        $p6->price = 880;
        $p6->description = '週間の予定を書き込める、大きめサイズのスケジュール付箋。デスクまわりでも使いやすい、予定管理に便利な文房具です。';
        $p6->image = 'images/products/schedule-sticky-notes.webp';
        $p6->category_id = $notebook->id;
        $p6->is_active = true;
        $p6->save();

        $p7 = new Product();
        $p7->name = 'JUMBO RING SKETCH BOOK';
        $p7->price = 1650;
        $p7->description = '存在感のある大きなリングが特徴の、丈夫なスケッチブック。自然な白色の無酸紙を使用し、絵やイラストを描くためのノートとして使えます。';
        $p7->image = 'images/products/jumbo-ring-sketch-book.webp';
        $p7->category_id = $notebook->id;
        $p7->is_active = true;
        $p7->save();

        $p8 = new Product();
        $p8->name = 'CUSTARD SLICE NOTEBOOK';
        $p8->price = 2200;
        $p8->description = 'カスタードスライスを思わせるユニークなデザインのノート。異なる3種類の紙を組み合わせ、ページを切り離して使えるミシン目も備えています。';
        $p8->image = 'images/products/custard-slice-notebook.webp';
        $p8->category_id = $notebook->id;
        $p8->is_active = true;
        $p8->save();

        // スタンプ

        $p9 = new Product();
        $p9->name = 'BLOCK SHAPE STAMP SET';
        $p9->price = 1980;
        $p9->description = '円や四角、三角などの形を組み合わせて、自由なパターンを作れるスタンプセット。付属のインクパッドですぐに楽しめます。';
        $p9->image = 'images/products/block-shape-stamp.webp';
        $p9->category_id = $rubberStamps->id;
        $p9->is_active = true;
        $p9->save();

        $p10 = new Product();
        $p10->name = 'ALPHABET STAMP SET';
        $p10->price = 3300;
        $p10->description = '活字のようなサンセリフ書体を楽しめるアルファベットスタンプ。木製のスタンプ33個をセットにし、ラベリングやカード作りにも使えます。';
        $p10->image = 'images/products/alphabet-stamp.webp';
        $p10->category_id = $rubberStamps->id;
        $p10->is_active = true;
        $p10->save();

        $p11 = new Product();
        $p11->name = 'PLANE GEOMETRY RUBBER STAMPS';
        $p11->price = 2750;
        $p11->description = '幾何学的な図形をモチーフにした、14個のラバースタンプセット。組み合わせて模様を作ったり、描画やデコレーションのアクセントとして楽しめます。';
        $p11->image = 'images/products/plane-geometry-rubber-stamps.webp';
        $p11->category_id = $rubberStamps->id;
        $p11->is_active = true;
        $p11->save();

        $p12 = new Product();
        $p12->name = 'PERPETUAL CALENDAR STAMP';
        $p12->price = 2200;
        $p12->description = '月ごとに組み替えて使える、カレンダー用のラバースタンプ。パーツを自由に並べ替えられるため、毎月新しいカレンダーを作れます。';
        $p12->image = 'images/products/perpetual-calendar-stamp.webp';
        $p12->category_id = $rubberStamps->id;
        $p12->is_active = true;
        $p12->save();

        // クリップ・ピン

        $p13 = new Product();
        $p13->name = 'BUTTERFLY CLIPS';
        $p13->price = 1320;
        $p13->description = 'バタフライ型のクリップ。1970年代のデッドストックで、ひとつひとつに味わいがあります。書類や紙をまとめる文房具として使えます。';
        $p13->image = 'images/products/butterfly-clips.webp';
        $p13->category_id = $clipsPins->id;
        $p13->is_active = true;
        $p13->save();

        $p14 = new Product();
        $p14->name = 'CARD CLIPS';
        $p14->price = 1100;
        $p14->description = '書き込みができる、大きめサイズのペーパークリップ。ページをまとめるだけでなく、見出しやタブとしても使える便利な文房具です。';
        $p14->image = 'images/products/card-clips.webp';
        $p14->category_id = $clipsPins->id;
        $p14->is_active = true;
        $p14->save();

        $p15 = new Product();
        $p15->name = 'CORNER CLIPS';
        $p15->price = 880;
        $p15->description = '紙の角をしっかりとまとめながら、ページをめくりやすいよう設計されたコーナークリップ。ゴールドとシルバーから選べます。';
        $p15->image = 'images/products/corner-clips.webp';
        $p15->category_id = $clipsPins->id;
        $p15->is_active = true;
        $p15->save();

        $p16 = new Product();
        $p16->name = 'XL WOOD DESK PEG';
        $p16->price = 1980;
        $p16->description = '大きなブナ材のデスクペグ。写真を飾ったり、書類の束をまとめたりと、デスクまわりで幅広く使える文房具です。';
        $p16->image = 'images/products/xl-wood-desk-peg.webp';
        $p16->category_id = $clipsPins->id;
        $p16->is_active = true;
        $p16->save();
    }
}