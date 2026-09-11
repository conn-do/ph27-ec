<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Tests\TestCase;

test('shows the published products', function () {
    /** @var TestCase $this */
    Product::factory()->create(['name' => 'ドラゴンえんぴつ']);
    Product::factory()->unpublished()->create(['name' => 'ないしょの しょうひん']);

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('ドラゴンえんぴつ')
        ->assertDontSee('ないしょの しょうひん');
});

test('shows the tax inclusive price on the card', function () {
    /** @var TestCase $this */
    Product::factory()->create(['name' => 'ほしぞらノート', 'price' => 200, 'tax_rate' => 10]);

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('ぜいこみ 220えん');
});

test('can filter by category', function () {
    /** @var TestCase $this */
    $pencils = Category::factory()->create(['name' => 'えんぴつ', 'slug' => 'pencil']);
    $snacks = Category::factory()->create(['name' => 'おかし', 'slug' => 'snack']);

    Product::factory()->for($pencils)->create(['name' => 'ドラゴンえんぴつ']);
    Product::factory()->for($snacks)->create(['name' => 'ラムネ']);

    $this->get(route('home', ['category' => 'pencil']))
        ->assertSuccessful()
        ->assertSee('ドラゴンえんぴつ')
        ->assertDontSee('ラムネ');
});

test('can search by name', function () {
    /** @var TestCase $this */
    Product::factory()->create(['name' => 'にじいろマーカー']);
    Product::factory()->create(['name' => 'スティックのり']);

    $this->get(route('products.search', ['keyword' => 'マーカー']))
        ->assertSuccessful()
        ->assertSee('にじいろマーカー')
        ->assertDontSee('スティックのり');
});

test('says so when the search finds nothing', function () {
    /** @var TestCase $this */
    Product::factory()->create(['name' => 'スティックのり']);

    $this->get(route('products.search', ['keyword' => 'ぞうさん']))
        ->assertSuccessful()
        ->assertSee('しょうひんが みつからなかったよ。');
});

test('can sort by price', function () {
    /** @var TestCase $this */
    Product::factory()->create(['name' => 'たかいもの', 'price' => 1000]);
    Product::factory()->create(['name' => 'やすいもの', 'price' => 50]);

    $response = $this->get(route('home', ['sort' => 'cheap']))->assertSuccessful();

    expect($response->viewData('products')->pluck('name')->all())
        ->toBe(['やすいもの', 'たかいもの']);
});

test('shows the product detail with its price breakdown', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create([
        'name' => 'キラキラゲルペン',
        'price' => 300,
        'tax_rate' => 10,
        'stock' => 5,
    ]);

    $this->get(route('products.show', $product))
        ->assertSuccessful()
        ->assertSee('キラキラゲルペン')
        ->assertSee('ほんたいの ねだん')
        ->assertSee('330えん')      // はらう ねだん
        ->assertSee('ざいこ あり');  // ざいこの ひょうじ
});

test('warns when only a few are left', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create(['stock' => 2]);

    $this->get(route('products.show', $product))
        ->assertSuccessful()
        ->assertSee('のこり 2こ');
});

test('hides an unpublished product detail', function () {
    /** @var TestCase $this */
    $product = Product::factory()->unpublished()->create();

    $this->get(route('products.show', $product))->assertNotFound();
});

test('shows a sold out product as sold out', function () {
    /** @var TestCase $this */
    $product = Product::factory()->outOfStock()->create();

    $this->get(route('products.show', $product))
        ->assertSuccessful()
        ->assertSee('いまは うりきれ です');
});

test('shows the average rating of the reviews', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create();

    Review::factory()->for($product)->for(User::factory())->create(['rating' => 5]);
    Review::factory()->for($product)->for(User::factory())->create(['rating' => 3]);

    $response = $this->get(route('products.show', $product))->assertSuccessful();

    expect((float) $response->viewData('product')->reviews_avg_rating)->toBe(4.0);
});
