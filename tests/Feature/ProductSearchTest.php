<?php

use App\Models\Category;
use App\Models\Product;

test('キーワードで商品名を検索できる', function () {
    $pen = Product::factory()->create(['name' => 'すごいペン']);
    $notebook = Product::factory()->create(['name' => 'きれいなノート']);

    $response = $this->get('/search?keyword=ペン');

    $response->assertOk();
    $response->assertSee($pen->name);
    $response->assertDontSee($notebook->name);
});

test('価格の下限で絞り込める', function () {
    $cheap = Product::factory()->create(['name' => '安い商品', 'price' => 100]);
    $expensive = Product::factory()->create(['name' => '高い商品', 'price' => 5000]);

    $response = $this->get('/search?min_price=1000');

    $response->assertOk();
    $response->assertSee($expensive->name);
    $response->assertDontSee($cheap->name);
});

test('価格の上限で絞り込める', function () {
    $cheap = Product::factory()->create(['name' => '安い商品', 'price' => 100]);
    $expensive = Product::factory()->create(['name' => '高い商品', 'price' => 5000]);

    $response = $this->get('/search?max_price=1000');

    $response->assertOk();
    $response->assertSee($cheap->name);
    $response->assertDontSee($expensive->name);
});

test('在庫ありのみで絞り込める', function () {
    $inStock = Product::factory()->create(['name' => '在庫あり商品', 'stock' => 5]);
    $outOfStock = Product::factory()->create(['name' => '売り切れ商品', 'stock' => 0]);

    $response = $this->get('/search?in_stock_only=1');

    $response->assertOk();
    $response->assertSee($inStock->name);
    $response->assertDontSee($outOfStock->name);
});

test('キーワードと価格帯を組み合わせて絞り込める', function () {
    $match = Product::factory()->create(['name' => 'すごいペン', 'price' => 500]);
    $wrongPrice = Product::factory()->create(['name' => 'すごい鉛筆', 'price' => 5000]);
    $wrongKeyword = Product::factory()->create(['name' => 'きれいなノート', 'price' => 500]);

    $response = $this->get('/search?keyword=すごい&max_price=1000');

    $response->assertOk();
    $response->assertSee($match->name);
    $response->assertDontSee($wrongPrice->name);
    $response->assertDontSee($wrongKeyword->name);
});

test('絞り込み条件がなければ全商品が表示される', function () {
    Product::factory()->count(3)->create();

    $response = $this->get('/search');

    $response->assertOk();
    expect(Product::count())->toBe(3);
});

test('商品一覧を価格が安い順に並び替えられる', function () {
    $expensive = Product::factory()->create(['name' => '高い商品', 'price' => 5000]);
    $cheap = Product::factory()->create(['name' => '安い商品', 'price' => 100]);

    $response = $this->get('/?sort=price_asc');

    $response->assertOk();
    $cheapPosition = strpos($response->getContent(), $cheap->name);
    $expensivePosition = strpos($response->getContent(), $expensive->name);
    expect($cheapPosition)->toBeLessThan($expensivePosition);
});

test('商品一覧を価格が高い順に並び替えられる', function () {
    $expensive = Product::factory()->create(['name' => '高い商品', 'price' => 5000]);
    $cheap = Product::factory()->create(['name' => '安い商品', 'price' => 100]);

    $response = $this->get('/?sort=price_desc');

    $response->assertOk();
    $cheapPosition = strpos($response->getContent(), $cheap->name);
    $expensivePosition = strpos($response->getContent(), $expensive->name);
    expect($expensivePosition)->toBeLessThan($cheapPosition);
});

test('検索結果も価格順に並び替えられる', function () {
    $expensive = Product::factory()->create(['name' => 'すごい高い商品', 'price' => 5000]);
    $cheap = Product::factory()->create(['name' => 'すごい安い商品', 'price' => 100]);

    $response = $this->get('/search?keyword=すごい&sort=price_asc');

    $response->assertOk();
    $cheapPosition = strpos($response->getContent(), $cheap->name);
    $expensivePosition = strpos($response->getContent(), $expensive->name);
    expect($cheapPosition)->toBeLessThan($expensivePosition);
});

test('カテゴリページの商品も価格順に並び替えられる', function () {
    $category = Category::factory()->create();
    $expensive = Product::factory()->create(['name' => '高い商品', 'price' => 5000, 'category_id' => $category->id]);
    $cheap = Product::factory()->create(['name' => '安い商品', 'price' => 100, 'category_id' => $category->id]);

    $response = $this->get("/categories/{$category->slug}?sort=price_asc");

    $response->assertOk();
    $cheapPosition = strpos($response->getContent(), $cheap->name);
    $expensivePosition = strpos($response->getContent(), $expensive->name);
    expect($cheapPosition)->toBeLessThan($expensivePosition);
});
