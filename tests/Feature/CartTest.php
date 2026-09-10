<?php

use App\Models\Product;
use Inertia\Testing\AssertableInertia as Assert;

test('guests can view an empty cart', function () {
    $this->get(route('cart.index'))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('cart/index')
        ->where('auth.user', null)
        ->where('cart.items', [])
        ->where('cart.quantity', 0)
        ->where('cart.total', 0)
    );
});

test('active products can be added to the session cart and quantities are combined', function () {
    $product = Product::factory()->create(['price' => 880, 'stock' => 5]);

    $this->from(route('products.show', ['product' => $product->slug]))
        ->post(route('cart.store', ['product' => $product->slug]))
        ->assertRedirect(route('products.show', ['product' => $product->slug]))
        ->assertSessionHas('paperloop.cart.'.$product->id, 1);

    $this->from(route('cart.index'))
        ->post(route('cart.store', ['product' => $product->slug]), ['quantity' => 2])
        ->assertRedirect(route('cart.index'))
        ->assertSessionHas('paperloop.cart.'.$product->id, 3);

    $this->get(route('cart.index'))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('cart.quantity', 3)
        ->where('cart.total', 2640)
        ->has('cart.items', 1)
        ->where('cart.items.0.product.id', $product->id)
        ->where('cart.items.0.quantity', 3)
        ->where('cart.items.0.subtotal', 2640)
    );
});

test('cart quantity is shared with shop and product pages after adding an item', function () {
    $product = Product::factory()->create(['stock' => 3]);
    $this->withSession(['paperloop.cart' => [$product->id => 1]]);

    $this->get(route('shop'))->assertInertia(fn (Assert $page) => $page
        ->where('cartQuantity', 1)
    );

    $this->post(route('cart.store', ['product' => $product->slug]))
        ->assertSessionHas('paperloop.cart.'.$product->id, 2);

    $this->get(route('products.show', ['product' => $product->slug]))
        ->assertInertia(fn (Assert $page) => $page
            ->where('cartQuantity', 2)
        );
});

test('cart quantity can be increased decreased and directly updated', function () {
    $product = Product::factory()->create(['price' => 1320, 'stock' => 5]);
    $this->withSession(['paperloop.cart' => [$product->id => 2]]);

    $this->patch(route('cart.update', ['product' => $product->slug]), ['quantity' => 3])
        ->assertRedirect(route('cart.index'))
        ->assertSessionHas('success', '数量を更新しました。')
        ->assertSessionHas('paperloop.cart.'.$product->id, 3);

    $this->patch(route('cart.update', ['product' => $product->slug]), ['quantity' => 2])
        ->assertSessionHas('paperloop.cart.'.$product->id, 2);

    $this->get(route('cart.index'))->assertInertia(fn (Assert $page) => $page
        ->where('cart.quantity', 2)
        ->where('cart.total', 2640)
        ->where('cart.items.0.subtotal', 2640)
    );
});

test('browser form method spoofing updates the session cart summary', function () {
    $product = Product::factory()->create(['price' => 660, 'stock' => 5]);
    $this->withSession(['paperloop.cart' => [$product->id => 4]]);

    $this->post(route('cart.update', ['product' => $product->slug]).'?_method=PATCH', ['quantity' => 2])
        ->assertRedirect(route('cart.index'))
        ->assertSessionHas('paperloop.cart.'.$product->id, 2);

    $this->get(route('cart.index'))->assertInertia(fn (Assert $page) => $page
        ->where('cart.quantity', 2)
        ->where('cart.items.0.subtotal', 1320)
        ->where('cart.total', 1320)
    );
});

test('cart items can be removed', function () {
    $first = Product::factory()->create();
    $second = Product::factory()->create();
    $this->withSession(['paperloop.cart' => [$first->id => 1, $second->id => 2]]);

    $this->delete(route('cart.destroy', ['product' => $first->slug]))
        ->assertRedirect(route('cart.index'))
        ->assertSessionHas('success', '商品をカートから削除しました。')
        ->assertSessionMissing('paperloop.cart.'.$first->id)
        ->assertSessionHas('paperloop.cart.'.$second->id, 2);

    $this->delete(route('cart.destroy', ['product' => $second->slug]))
        ->assertSessionMissing('paperloop.cart');
});

test('stock limits prevent over-adding and over-updating without changing the cart', function () {
    $product = Product::factory()->create(['stock' => 2]);

    $this->from(route('cart.index'))
        ->post(route('cart.store', ['product' => $product->slug]), ['quantity' => 2])
        ->assertSessionHas('paperloop.cart.'.$product->id, 2);

    $this->from(route('cart.index'))
        ->post(route('cart.store', ['product' => $product->slug]))
        ->assertRedirect(route('cart.index'))
        ->assertSessionHas('error', '在庫数を超える数量はカートに追加できません。')
        ->assertSessionHas('paperloop.cart.'.$product->id, 2);

    $this->patch(route('cart.update', ['product' => $product->slug]), ['quantity' => 3])
        ->assertRedirect(route('cart.index'))
        ->assertSessionHas('error', '在庫数を超える数量には変更できません。')
        ->assertSessionHas('paperloop.cart.'.$product->id, 2);
});

test('out of stock and inactive products cannot be added', function (array $attributes, string $message) {
    $product = Product::factory()->create($attributes);

    $this->from(route('shop'))
        ->post(route('cart.store', ['product' => $product->slug]))
        ->assertRedirect(route('shop'))
        ->assertSessionHas('error', $message)
        ->assertSessionMissing('paperloop.cart');
})->with([
    'out of stock' => [['stock' => 0], 'この商品は在庫切れです。'],
    'inactive' => [['is_active' => false, 'stock' => 10], 'この商品は現在カートに追加できません。'],
]);

test('inactive or removed products are not retained in a cart summary', function () {
    $inactive = Product::factory()->create(['is_active' => false]);
    $active = Product::factory()->create(['price' => 440]);
    $this->withSession(['paperloop.cart' => [$inactive->id => 2, $active->id => 1, 999999 => 1]]);

    $this->get(route('cart.index'))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('cart.quantity', 1)
        ->where('cart.total', 440)
        ->has('cart.items', 1)
        ->where('cart.items.0.product.id', $active->id)
    )->assertSessionHas('paperloop.cart', [$active->id => 1]);
});

test('cart uses the current product price for totals', function () {
    $product = Product::factory()->create(['price' => 880, 'stock' => 5]);
    $this->withSession(['paperloop.cart' => [$product->id => 2]]);
    $product->update(['price' => 990]);

    $this->get(route('cart.index'))->assertInertia(fn (Assert $page) => $page
        ->where('cart.items.0.product.price', 990)
        ->where('cart.items.0.subtotal', 1980)
        ->where('cart.total', 1980)
    );
});

test('cart validation rejects invalid quantities', function (array $data, string $message) {
    $product = Product::factory()->create(['stock' => 5]);

    $this->from(route('cart.index'))
        ->post(route('cart.store', ['product' => $product->slug]), $data)
        ->assertRedirect(route('cart.index'))
        ->assertSessionHasErrors(['quantity' => $message])
        ->assertSessionMissing('paperloop.cart');
})->with([
    'zero' => [['quantity' => 0], '数量は1以上で指定してください。'],
    'decimal' => [['quantity' => '1.5'], '数量は整数で指定してください。'],
    'array' => [['quantity' => [1]], '数量は整数で指定してください。'],
]);
