<?php

use App\Models\Category;
use App\Models\News;
use App\Models\Product;

test('home page shows the storefront sections', function () {
    $category = Category::create([
        'name' => 'ペン',
        'slug' => 'pens',
    ]);

    Product::create([
        'category_id' => $category->id,
        'name' => 'ミニマルペン',
        'price' => 500,
        'description' => '書きやすいペン',
        'image' => 'products/pen.jpg',
        'stock' => 10,
    ]);

    News::create([
        'title' => '新商品のお知らせ',
        'content' => '文房具を追加しました。',
    ]);

    $response = $this->get(route('home'));

    $response
        ->assertSuccessful()
        ->assertSee('カテゴリ')
        ->assertSee('ペン')
        ->assertSee('ミニマルペン')
        ->assertSee('product-card-meta', false)
        ->assertSee('¥500')
        ->assertSee('お知らせ')
        ->assertSee('新商品のお知らせ')
        ->assertSee('store-footer-sitemap', false)
        ->assertSee('ショッピング');
});

test('search page keeps the category navigation visible', function () {
    $category = Category::create([
        'name' => 'ノート',
        'slug' => 'notes',
    ]);

    Product::create([
        'category_id' => $category->id,
        'name' => '方眼ノート',
        'price' => 700,
        'description' => '使いやすいノート',
        'image' => 'products/note.jpg',
        'stock' => 8,
    ]);

    $response = $this->get('/search?keyword=方眼');

    $response
        ->assertSuccessful()
        ->assertSee('ノート')
        ->assertSee('方眼ノート')
        ->assertSee('検索結果をクリア');
});
