<?php

use App\Models\Product;

test('在庫の範囲内ならカートに追加できる', function () {
    $product = Product::factory()->create(['stock' => 5]);

    $response = $this->post('/cart', [
        'productId' => $product->id,
        'quantity' => 3,
    ]);

    $response->assertRedirect('/cart');
    expect(session('cart'))->toBe([$product->id => 3]);
});

test('在庫を超える数量はカートに追加できない', function () {
    $product = Product::factory()->create(['stock' => 2]);

    $response = $this->post('/cart', [
        'productId' => $product->id,
        'quantity' => 3,
    ]);

    $response->assertSessionHasErrors('quantity');
    expect(session('cart'))->toBeNull();
});

test('カート内商品の数量を変更できる', function () {
    $product = Product::factory()->create(['stock' => 5]);
    $this->withSession(['cart' => [$product->id => 2]]);

    $response = $this->post("/cart/{$product->id}", [
        'quantity' => 4,
    ]);

    $response->assertRedirect('/cart');
    expect(session('cart'))->toBe([$product->id => 4]);
});

test('在庫を超える数量には変更できない', function () {
    $product = Product::factory()->create(['stock' => 2]);
    $this->withSession(['cart' => [$product->id => 1]]);

    $response = $this->post("/cart/{$product->id}", [
        'quantity' => 3,
    ]);

    $response->assertSessionHasErrors('quantity');
    expect(session('cart'))->toBe([$product->id => 1]);
});

test('カートから商品を削除できる', function () {
    $product = Product::factory()->create(['stock' => 5]);
    $this->withSession(['cart' => [$product->id => 2]]);

    $response = $this->get("/cart/{$product->id}/remove");

    $response->assertRedirect('/cart');
    expect(session('cart'))->toBe([]);
});

test('商品詳細ページの個数入力の上限は在庫数と10個の小さい方になる', function () {
    $lowStock = Product::factory()->create(['stock' => 3]);
    $highStock = Product::factory()->create(['stock' => 50]);

    $this->get("/products/{$lowStock->id}")->assertSee('max="3"', false);
    $this->get("/products/{$highStock->id}")->assertSee('max="10"', false);
});

test('カートページの個数入力の上限は在庫数と10個の小さい方になる', function () {
    $product = Product::factory()->create(['stock' => 3]);
    $this->withSession(['cart' => [$product->id => 1]]);

    $this->get('/cart')->assertSee('max="3"', false);
});
