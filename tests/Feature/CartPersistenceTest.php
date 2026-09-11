<?php

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

test('ログイン中にカートへ追加するとDBに保存される', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 5]);

    $this->actingAs($user)->post('/cart', [
        'productId' => $product->id,
        'quantity' => 2,
    ])->assertRedirect('/cart');

    expect(CartItem::where('user_id', $user->id)->where('product_id', $product->id)->first()->quantity)->toBe(2);
    expect(session('cart'))->toBeNull();
});

test('ゲストのカートに追加するとセッションに保存されDBには保存されない', function () {
    $product = Product::factory()->create(['stock' => 5]);

    $this->post('/cart', [
        'productId' => $product->id,
        'quantity' => 2,
    ])->assertRedirect('/cart');

    expect(session('cart'))->toBe([$product->id => 2]);
    expect(CartItem::count())->toBe(0);
});

test('ログインするとゲスト時代のセッションカートがDBカートへ統合される', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 5]);

    $this->withSession(['cart' => [$product->id => 3]])
        ->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

    $this->assertAuthenticated();
    expect(CartItem::where('user_id', $user->id)->where('product_id', $product->id)->first()->quantity)->toBe(3);
    expect(session('cart'))->toBeNull();
});

test('ログイン時、DBに既にある商品はセッションの数量が合算される', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 10]);
    CartItem::factory()->create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 2]);

    $this->withSession(['cart' => [$product->id => 3]])
        ->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

    expect(CartItem::where('user_id', $user->id)->where('product_id', $product->id)->first()->quantity)->toBe(5);
});
