<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        \App\Models\Product::query()->delete();

        // 外部キー制約を再有効化
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // =========================================================
        // ① 既存・追加画像の処理
        // =========================================================
        $images = [
            // 基本・既存画像
            'pen.png', 'pen_skyblue.png', 'pen_black.png',
            'note.png', 'note_milky.png', 'note_mint.png',
            'pencil.png', 'marker.jpg', 'marker_cool.png',
            'pencase_black.png', 'pencase_beige.png', 'pencase_navy.png',
            'organizer.jpg', 'case.jpg',

            // 追加画像・カラーバリエーション
            'ball_pen_black.png', 'ball_pen_red.png', 'ball_pen_blue.png',
            '4ball_pen.png', 'premium_ball_pen.png', 'ball_pen_0.28.png', '3ball_pen.png',
            'shape_pen.png', 'shape_pen_darkbrown.png', 'shape_pencil.png', 'enpitu_shape_pen.png','shapen.png',
            'marker_warm.png', 'marker_cool.png',
            '12marker.png', '3marker.png',
            '万年筆.png', '万年筆インク.png', '高級万年筆.png',
            'けしごむ.png', '修正テープ.png', 'ペン型けしごむ.png',
            'ノート.png', 'ミニノート.png', '防水ノート.png', 'ドットノート.png',
            'ルーズリーフ.png', 'バインダーノート.png', '手帳ノート.png', 'ミニカレンダー.png',
            '本革_キャメル.png', '本革_ダークブラウン.png', '本革_ブラック.png',
            'スタイリッシュ.png', 'トレー.png', '13.png', 'リングファイル.png',
            'ハサミ.png', 'ペーパーカッター.png', 'テープのり.png', 'マステ.png', '電卓.png',
            '12色.png', '絵具.png', 'スケッチブック.png', 'クロッキー.png'
        ];

        foreach ($images as $img) {
            if (file_exists(public_path("images/products/{$img}"))) {
                Storage::disk('public')->put(
                    "images/products/{$img}",
                    file_get_contents(public_path("images/products/{$img}"))
                );
            }
        }

        // =========================================================
        // ② カテゴリー（親・サブ）の構築
        // =========================================================
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

        $subCategoryMap = [];

        foreach ($categoriesData as $parentName => $data) {
            $parent = Category::firstOrCreate(
                ['slug' => $data['slug']],
                ['name' => $parentName, 'parent_id' => null]
            );

            foreach ($data['subs'] as $sub) {
                $subCat = Category::firstOrCreate(
                    ['slug' => $sub['slug']],
                    ['name' => $sub['name'], 'parent_id' => $parent->id]
                );
                $subCategoryMap[$sub['name']] = $subCat->id;
            }
        }

        // =========================================================
        // ③ 商品データの一括定義
        // =========================================================
        $products = [
            // --- 既存ベース商品 ---
            [
                'name' => 'すごいペン',
                'description' => '抜群の書き味となめらかさを備えた極上ペン。',
                'price' => 250,
                'sub_category' => 'ボールペン',
                'image' => 'images/products/pen.png',
                'is_sale' => true,
                'colors' => [
                    'サクラピンク' => [
                        'code' => '#f472b6',
                        'image' => 'images/products/pen.png',
                    ],
                    'スカイブルー' => [
                        'code' => '#38bdf8',
                        'image' => 'images/products/pen_skyblue.png',
                    ],
                    'ブラック' => [
                        'code' => '#1e293b',
                        'image' => 'images/products/pen_black.png',
                    ],
                ]
            ],
            [
                'name' => 'きれいなノート',
                'description' => '裏抜けしにくい上質紙を使用した美しいノート。',
                'price' => 650,
                'sub_category' => 'ノート・メモ帳',
                'image' => 'images/products/note.png',
                'is_sale' => true,
                'colors' => [
                    'アイスブルー' => [
                        'code' => '#cbd5e1',
                        'image' => 'images/products/note.png',
                    ],
                    'ミルキーホワイト' => [
                        'code' => '#f8fafc',
                        'image' => 'images/products/note_milky.png',
                    ],
                    'ミントグリーン' => [
                        'code' => '#86efac',
                        'image' => 'images/products/note_mint.png',
                    ],
                ]
            ],
            [
                'name' => 'よく消える鉛筆',
                'description' => '濃く書けてきれいに消せる定番の鉛筆。',
                'price' => 200,
                'sub_category' => 'シャープペンシル',
                'image' => 'images/products/pencil.png',
                'is_sale' => true
            ],
            [
                'name' => 'カラフルマーカー 5色セット',
                'description' => 'ノート整理に映える鮮やかな5色セット。',
                'price' => 750,
                'sub_category' => 'マーカー・蛍光ペン',
                'image' => 'images/products/marker.jpg',
                'is_sale' => false
            ],
            [
                'name' => '自立する大容量ペンケース',
                'description' => 'デスクで立つ、タップリ入る便利なペンケース。',
                'price' => 1800,
                'sub_category' => 'ペンケース',
                'image' => 'images/products/pencase_black.png',
                'is_sale' => true,
                'colors' => [
                    'ブラック' => [
                        'code' => '#0f172a',
                        'image' => 'images/products/pencase_black.png',
                    ],
                    'ベージュ' => [
                        'code' => '#d4d4d8',
                        'image' => 'images/products/pencase_beige.png',
                    ],
                    'ネイビー' => [
                        'code' => '#1e3a8a',
                        'image' => 'images/products/pencase_navy.png',
                    ],
                ]
            ],
            [
                'name' => '透明デスクオーガナイザー',
                'description' => '中身が一目でわかるクリアな卓上整理ラック。',
                'price' => 2400,
                'sub_category' => 'デスク整理用品',
                'image' => 'images/products/organizer.jpg',
                'is_sale' => false
            ],
            [
                'name' => '持ち運び書類キャリングケース',
                'description' => 'A4書類を折らずに運べる丈夫なケース。',
                'price' => 1200,
                'sub_category' => 'ファイル・バインダー',
                'image' => 'images/products/case.jpg',
                'is_sale' => false
            ],

            // --- ボールペン ---
            [
                'name' => '低摩擦なめらか油性ボールペン 0.5mm',
                'description' => '軽やかな書き心地で長時間の筆記も快適。',
                'price' => 180,
                'sub_category' => 'ボールペン',
                'image' => 'images/products/ball_pen_black.png',
                'is_sale' => false,
                'colors' => [
                    'ブラック' => [
                        'code' => '#000000',
                        'image' => 'images/products/ball_pen_black.png',
                    ],
                    'レッド' => [
                        'code' => '#ef4444',
                        'image' => 'images/products/ball_pen_red.png',
                    ],
                    'ブルー' => [
                        'code' => '#2563eb',
                        'image' => 'images/products/ball_pen_blue.png',
                    ],
                ]
            ],
            ['name' => '4色マルチゲルインクボールペン', 'description' => '手帳の色分け整理に最適な高発色ペン。', 'price' => 500, 'sub_category' => 'ボールペン', 'image' => 'images/products/4ball_pen.png', 'is_sale' => true],
            ['name' => 'プレミアム真鍮製レトロボールペン', 'description' => '重厚感とクラシックな美しさが際立つ一本。', 'price' => 3200, 'sub_category' => 'ボールペン', 'image' => 'images/products/premium_ball_pen.png', 'is_sale' => false],
            ['name' => '超極細水性ボールペン 0.28mm', 'description' => '細かい方眼ノートへの書き込みにぴったり。', 'price' => 220, 'sub_category' => 'ボールペン', 'image' => 'images/products/ball_pen_0.28.png', 'is_sale' => false],
            ['name' => '消せる3色ゲルボールペン', 'description' => 'こすると消える便利な多色ペン。', 'price' => 680, 'sub_category' => 'ボールペン', 'image' => 'images/products/3ball_pen.png', 'is_sale' => false],

            // --- シャープペンシル ---
            [
                'name' => '製図用木軸シャープ 0.5mm',
                'description' => '木のぬくもりと低重心でブレない安定感。',
                'price' => 1600,
                'sub_category' => 'シャープペンシル',
                'image' => 'images/products/shape_pen.png',
                'is_sale' => false,
                'colors' => [
                    'ナチュラル' => [
                        'code' => '#d97706',
                        'image' => 'images/products/shape_pen.png',
                    ],
                    'ダークブラウン' => [
                        'code' => '#451a03',
                        'image' => 'images/products/shape_pen_darkbrown.png',
                    ],
                ]
            ],
            ['name' => '自動芯繰り出しシャーペン', 'description' => 'ノックの手間なく連続で書き続けられる。', 'price' => 850, 'sub_category' => 'シャープペンシル', 'image' => 'images/products/shapen.png', 'is_sale' => true],
            ['name' => 'クッション機構搭載 折れないシャーペン', 'description' => '強い筆圧でも芯が折れにくい安心設計。', 'price' => 450, 'sub_category' => 'シャープペンシル', 'image' => 'images/products/shape_pencil.png', 'is_sale' => false],
            ['name' => 'クラシックデザイン鉛筆型シャーペン', 'description' => '鉛筆のような持ち心地のおしゃれなペン。', 'price' => 380, 'sub_category' => 'シャープペンシル', 'image' => 'images/products/enpitu_shape_pen.png', 'is_sale' => false],

            // --- マーカー・蛍光ペン ---
            [
                'name' => 'くすみカラーラインマーカー 5色セット',
                'description' => '目に優しくノートが上品に整う淡い色合い。',
                'price' => 600,
                'sub_category' => 'マーカー・蛍光ペン',
                'image' => 'images/products/marker_warm.png',
                'is_sale' => true,
                'colors' => [
                    'ウォームセット' => [
                        'code' => '#f87171',
                        'image' => 'images/products/marker_warm.png',
                    ],
                    'クールセット' => [
                        'code' => '#60a5fa',
                        'image' => 'images/products/marker_cool.png',
                    ],
                ]
            ],
            ['name' => '水性アートペン 12色イラストセット', 'description' => 'グラデーションも自由自在なカラーペン。', 'price' => 1800, 'sub_category' => 'マーカー・蛍光ペン', 'image' => 'images/products/12marker.png', 'is_sale' => false],
            ['name' => 'ウインドウ付きツイン蛍光ペン 3色', 'description' => '文字が見えて引きやすい透明窓付きチップ。', 'price' => 360, 'sub_category' => 'マーカー・蛍光ペン', 'image' => 'images/products/3marker.png', 'is_sale' => false],

            // --- 万年筆 ---
            ['name' => '透明軸エントリー万年筆 (F細字)', 'description' => 'インクの流れを見て楽しめるクリアボディ。', 'price' => 1500, 'sub_category' => '万年筆', 'image' => 'images/products/万年筆.png', 'is_sale' => false],
            ['name' => 'プレミアムボトルインク ミッドナイトブルー', 'description' => '発色が美しく書くのが楽しくなる万年筆インク。', 'price' => 1650, 'sub_category' => '万年筆', 'image' => 'images/products/万年筆インク.png', 'is_sale' => true],
            ['name' => '木製ギフトボックス入り 万年筆セット', 'description' => '大切な人への贈り物にもおすすめの逸品。', 'price' => 5800, 'sub_category' => '万年筆', 'image' => 'images/products/高級万年筆.png', 'is_sale' => false],

            // --- 消しゴム・修正用品 ---
            ['name' => 'かるい力で消せるプレミアム消しゴム 3個', 'description' => '消しくずがまとまりやすい高品質消しゴム。', 'price' => 300, 'sub_category' => '消しゴム・修正用品', 'image' => 'images/products/けしごむ.png', 'is_sale' => false],
            ['name' => '静音設計ミニ修正テープ 5mm×6m', 'description' => 'カチカチ音が響かないスムーズなテープ。', 'price' => 250, 'sub_category' => '消しゴム・修正用品', 'image' => 'images/products/修正テープ.png', 'is_sale' => false],
            ['name' => 'ペン型細部用ホルダー消しゴム', 'description' => '細かい一文字だけをピンポイントで修正。', 'price' => 350, 'sub_category' => '消しゴム・修正用品', 'image' => 'images/products/ペン型けしごむ.png', 'is_sale' => false],

            // --- ノート・メモ帳 ---
            ['name' => '180度フラットに開く方眼ノート A5', 'description' => '中央が浮き上がらず見開きで使いやすい。', 'price' => 880, 'sub_category' => 'ノート・メモ帳', 'image' => 'images/products/ノート.png', 'is_sale' => false],
            ['name' => 'クラフト表紙 ミニメモ帳 4冊セット', 'description' => 'ポケットにすっぽり収まるメモ。', 'price' => 450, 'sub_category' => 'ノート・メモ帳', 'image' => 'images/products/ミニノート.png', 'is_sale' => true],
            ['name' => '全天候型 防水リングメモ B6', 'description' => '雨の日や屋外での作業でも使える頑丈な紙。', 'price' => 520, 'sub_category' => 'ノート・メモ帳', 'image' => 'images/products/防水ノート.png', 'is_sale' => false],
            ['name' => 'ドット方眼ソフトカバーノート B5', 'description' => '自由なレイアウトで図や文字が書ける。', 'price' => 700, 'sub_category' => 'ノート・メモ帳', 'image' => 'images/products/ドットノート.png', 'is_sale' => false],

            // --- ルーズリーフ / 手帳 ---
            ['name' => 'サラサラ書けるルーズリーフ B5 (100枚)', 'description' => 'にじみにくく滑らかな国産用紙。', 'price' => 380, 'sub_category' => 'ルーズリーフ', 'image' => 'images/products/ルーズリーフ.png', 'is_sale' => false],
            ['name' => '26穴リングファイル付き バインダーセット', 'description' => '書類とルーズリーフをすっきり一括管理。', 'price' => 950, 'sub_category' => 'ルーズリーフ', 'image' => 'images/products/バインダーノート.png', 'is_sale' => false],
            ['name' => 'マンスリー＆ウィークリー手帳 A5', 'description' => '目標達成やスケジューリングをサポート。', 'price' => 1400, 'sub_category' => '手帳・カレンダー', 'image' => 'images/products/手帳ノート.png', 'is_sale' => true],
            ['name' => '卓上ミニカレンダー 2026年版', 'description' => 'デスクで場所をとらないコンパクトサイズ。', 'price' => 600, 'sub_category' => '手帳・カレンダー', 'image' => 'images/products/ミニカレンダー.png', 'is_sale' => false],

            // --- ペンケース / デスク整理 / ファイル ---
            [
                'name' => 'ロールタイプ 本革ペンケース',
                'description' => '使い込むほど味わいが増す本革ロールケース。',
                'price' => 3800,
                'sub_category' => 'ペンケース',
                'image' => 'images/products/本革_キャメル.png',
                'is_sale' => false,
                'colors' => [
                    'キャメル' => [
                        'code' => '#b45309',
                        'image' => 'images/products/本革_キャメル.png',
                    ],
                    'ダークブラウン' => [
                        'code' => '#451a03',
                        'image' => 'images/products/本革_ダークブラウン.png',
                    ],
                    'ブラック' => [
                        'code' => '#000000',
                        'image' => 'images/products/本革_ブラック.png',
                    ],
                ]
            ],
            ['name' => 'メッシュスリムペンポーチ', 'description' => '中身がうっすら見えて軽量なポーチ。', 'price' => 650, 'sub_category' => 'ペンケース', 'image' => 'images/products/スタイリッシュ.png', 'is_sale' => false],
            ['name' => 'スタッキング卓上書類トレー A4', 'description' => '重ねて省スペースに使える便利なトレイ。', 'price' => 980, 'sub_category' => 'デスク整理用品', 'image' => 'images/products/トレー.png', 'is_sale' => false],
            ['name' => '13ポケット アコーディオンファイル A4', 'description' => '領収書や伝票の分類整理に最適なケース。', 'price' => 1100, 'sub_category' => 'ファイル・バインダー', 'image' => 'images/products/13.png', 'is_sale' => true],
            ['name' => '2穴リングファイル 20mm厚 A4', 'description' => '書類をしっかり保護する丈夫な表紙。', 'price' => 420, 'sub_category' => 'ファイル・バインダー', 'image' => 'images/products/リングファイル.png', 'is_sale' => false],

            // --- 事務用品 ---
            ['name' => 'ベタつかないフッ素ハサミ', 'description' => '粘着テープを切っても刃がベタつかない。', 'price' => 850, 'sub_category' => 'ハサミ・カッター', 'image' => 'images/products/ハサミ.png', 'is_sale' => false],
            ['name' => '安全ガード付きペーパーカッター', 'description' => '直線カットが素早く綺麗にできる小型刃。', 'price' => 1200, 'sub_category' => 'ハサミ・カッター', 'image' => 'images/products/ペーパーカッター.png', 'is_sale' => false],
            ['name' => 'ドットタイプ 強粘着テープのり 3個', 'description' => '手を汚さずにスッと貼れる快適な使い心地。', 'price' => 580, 'sub_category' => 'テープ・のり', 'image' => 'images/products/テープのり.png', 'is_sale' => true],
            ['name' => '柄入り和紙マスキングテープ 5巻', 'description' => 'ラッピングや手帳の飾りに大活躍。', 'price' => 680, 'sub_category' => 'テープ・のり', 'image' => 'images/products/マステ.png', 'is_sale' => false],
            ['name' => 'キーボード風打鍵感 レトロ電卓 12桁', 'description' => '心地よいクリック感でおしゃれなデザイン。', 'price' => 1980, 'sub_category' => '電卓・計測機器', 'image' => 'images/products/電卓.png', 'is_sale' => true],

            // --- 画材 ---
            ['name' => '水溶性色鉛筆 24色缶入り', 'description' => '水を含ませた筆でなぞると水彩画風に。', 'price' => 2800, 'sub_category' => '色鉛筆・絵の具', 'image' => 'images/products/12色.png', 'is_sale' => false],
            ['name' => '固形水彩絵の具 18色 パレット・筆付き', 'description' => '屋外スケッチに便利なコンパクトセット。', 'price' => 2200, 'sub_category' => '色鉛筆・絵の具', 'image' => 'images/products/絵具.png', 'is_sale' => false],
            ['name' => '厚口水彩スケッチブック B5', 'description' => '水を含んでも波打ちにくい高品質紙。', 'price' => 750, 'sub_category' => 'スケッチブック', 'image' => 'images/products/スケッチブック.png', 'is_sale' => false],
            ['name' => 'クロッキーブック A4 100枚', 'description' => 'アイデアスケッチやデッサンに最適な用紙。', 'price' => 620, 'sub_category' => 'スケッチブック', 'image' => 'images/products/クロッキー.png', 'is_sale' => false],
        ];

        // =========================================================
        // ④ データベースへ登録
        // =========================================================
        foreach ($products as $item) {
            $catId = $subCategoryMap[$item['sub_category']] ?? null;

            $salePrice = null;
            if ($item['is_sale']) {
                $salePrice = floor($item['price'] * 0.8);
            }

            $imagePath = $item['image'] ?? 'https://images.unsplash.com/photo-1583485088034-697b5bc54ccd?w=500&auto=format&fit=crop';

            Product::create([
                'name'        => $item['name'],
                'description' => $item['description'],
                'price'       => $item['price'],
                'category_id' => $catId,
                'stock'       => rand(10, 50),
                'image'       => $imagePath,
                'is_sale'     => $item['is_sale'],
                'sale_price'  => $salePrice,
                'colors'      => $item['colors'] ?? null,
            ]);
        }
    }
}