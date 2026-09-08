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