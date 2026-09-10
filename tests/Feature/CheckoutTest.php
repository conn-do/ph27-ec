<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;

/** @return array{customer_name: string, postal_code: string, address: string, checkout_token: string} */
function orderData(string $token): array
{
    return [
        'customer_name' => '山田 花子',
        'postal_code' => '150-0001',
        'address' => '東京都渋谷区神宮前 1-2-3',
        'checkout_token' => $token,
    ];
}

test('guests cannot access checkout', function () {
    $this->get(route('checkout.index'))->assertRedirect(route('login'));
});

test('authenticated users can view checkout with their cart', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 660, 'stock' => 3]);
    $this->withSession(['paperloop.cart' => [$product->id => 2]]);

    $this->actingAs($user)
        ->get(route('checkout.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('checkout/index')
            ->where('cart.quantity', 2)
            ->where('cart.items.0.subtotal', 1320)
            ->where('cart.total', 1320)
            ->where('checkoutToken', fn ($token) => Str::isUuid($token))
        )
        ->assertSessionHas('paperloop.checkout_token');
});

test('the checkout page token can be used to place an order through the checkout route', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['name' => 'グリッドノート A5', 'price' => 880, 'stock' => 3]);
    $this->withSession(['paperloop.cart' => [$product->id => 1]]);

    $checkout = $this->actingAs($user)->get(route('checkout.index'))->assertOk();
    $token = $checkout->inertiaProps('checkoutToken');

    $this->post(route('checkout.store'), orderData($token))
        ->assertRedirect(route('checkout.complete', ['order' => 1]));

    $this->assertDatabaseHas('orders', ['id' => 1, 'user_id' => $user->id, 'total_price' => 880]);
    $this->assertDatabaseHas('order_items', [
        'order_id' => 1,
        'product_name' => 'グリッドノート A5',
        'price' => 880,
        'quantity' => 1,
    ]);
});

test('a checkout creates an order, snapshots items, decreases stock, and clears the cart', function () {
    $user = User::factory()->create();
    $first = Product::factory()->create(['name' => '真鍮クリップ', 'price' => 880, 'stock' => 5]);
    $second = Product::factory()->create(['name' => '方眼ノート', 'price' => 660, 'stock' => 4]);
    $token = Str::uuid()->toString();
    $this->withSession([
        'paperloop.cart' => [$first->id => 2, $second->id => 3],
        'paperloop.checkout_token' => $token,
    ]);

    $response = $this->actingAs($user)
        ->post(route('checkout.store'), orderData($token))
        ->assertRedirect();

    $order = Order::query()->sole();

    expect($order->user->is($user))->toBeTrue();
    expect($order->total_price)->toBe(3740);
    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'customer_name' => '山田 花子',
        'postal_code' => '150-0001',
        'address' => '東京都渋谷区神宮前 1-2-3',
        'total_price' => 3740,
    ]);
    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'product_id' => $first->id,
        'product_name' => '真鍮クリップ',
        'quantity' => 2,
        'price' => 880,
    ]);
    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'product_id' => $second->id,
        'product_name' => '方眼ノート',
        'quantity' => 3,
        'price' => 660,
    ]);
    $this->assertDatabaseHas('products', ['id' => $first->id, 'stock' => 3]);
    $this->assertDatabaseHas('products', ['id' => $second->id, 'stock' => 1]);
    $response->assertSessionMissing('paperloop.cart');
});

test('a checkout cannot be submitted twice with the same token', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 3]);
    $token = Str::uuid()->toString();
    $this->withSession([
        'paperloop.cart' => [$product->id => 1],
        'paperloop.checkout_token' => $token,
    ]);

    $this->actingAs($user)->post(route('checkout.store'), orderData($token));
    $this->post(route('checkout.store'), orderData($token))
        ->assertRedirect(route('checkout.index'))
        ->assertSessionHas('error', '注文情報の有効期限が切れました。もう一度お試しください。');

    expect(Order::query()->count())->toBe(1);
});

test('stock shortages roll back the entire order and retain the cart', function () {
    $user = User::factory()->create();
    $available = Product::factory()->create(['stock' => 3]);
    $shortage = Product::factory()->create(['stock' => 1]);
    $token = Str::uuid()->toString();
    $this->withSession([
        'paperloop.cart' => [$available->id => 2, $shortage->id => 2],
        'paperloop.checkout_token' => $token,
    ]);

    $this->actingAs($user)
        ->post(route('checkout.store'), orderData($token))
        ->assertRedirect(route('checkout.index'))
        ->assertSessionHas('error', '在庫が不足している商品があります。カートを確認してください。')
        ->assertSessionHas('paperloop.cart', [$available->id => 2, $shortage->id => 2]);

    expect(Order::query()->count())->toBe(0);
    $this->assertDatabaseHas('products', ['id' => $available->id, 'stock' => 3]);
    $this->assertDatabaseHas('products', ['id' => $shortage->id, 'stock' => 1]);
});

test('inactive products cannot be ordered', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['is_active' => false, 'stock' => 3]);
    $token = Str::uuid()->toString();
    $this->withSession([
        'paperloop.cart' => [$product->id => 1],
        'paperloop.checkout_token' => $token,
    ]);

    $this->actingAs($user)
        ->post(route('checkout.store'), orderData($token))
        ->assertRedirect(route('checkout.index'))
        ->assertSessionHas('error', '販売を終了した商品が含まれています。カートを確認してください。');

    expect(Order::query()->count())->toBe(0);
});

test('checkout validation errors are returned in Japanese without placing an order', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 3]);
    $token = Str::uuid()->toString();
    $this->withSession([
        'paperloop.cart' => [$product->id => 1],
        'paperloop.checkout_token' => $token,
    ]);

    $this->actingAs($user)
        ->from(route('checkout.index'))
        ->post(route('checkout.store'), [
            ...orderData($token),
            'customer_name' => '',
            'postal_code' => 'invalid',
            'address' => '',
        ])
        ->assertRedirect(route('checkout.index'))
        ->assertSessionHasErrors([
            'customer_name' => 'お名前を入力してください。',
            'postal_code' => '郵便番号は123-4567形式で入力してください。',
            'address' => '住所を入力してください。',
        ])
        ->assertSessionHas('paperloop.cart', [$product->id => 1]);

    expect(Order::query()->count())->toBe(0);
});

test('users can view only their own order completion page', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $order = Order::factory()->for($owner)->create();

    $this->actingAs($owner)
        ->get(route('checkout.complete', ['order' => $order]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('checkout/complete')
            ->where('order.id', $order->id)
        );

    $this->actingAs($otherUser)
        ->get(route('checkout.complete', ['order' => $order]))
        ->assertNotFound();
});
