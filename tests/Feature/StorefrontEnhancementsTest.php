<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

beforeEach(function () {
    $this->withoutVite();
    $this->category = Category::create(['name' => '筆記用具', 'slug' => 'writing']);
});

test('storefront and search pages render with categories', function () {
    createStorefrontProduct($this->category, ['name' => '深緑の万年筆']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('PAPER', false)
        ->assertSee('深緑の万年筆');

    $this->get(route('products.search', ['keyword' => '万年筆']))
        ->assertOk()
        ->assertSee('筆記用具')
        ->assertSee('深緑の万年筆');
});

test('authenticated user can favorite a product without duplicates', function () {
    $user = User::factory()->create();
    $product = createStorefrontProduct($this->category);

    $this->actingAs($user)->post(route('favorites.store', $product))->assertRedirect();
    $this->actingAs($user)->post(route('favorites.store', $product))->assertRedirect();

    expect($user->favorites()->whereBelongsTo($product)->count())->toBe(1);
});

test('guest cannot favorite or review products', function () {
    $product = createStorefrontProduct($this->category);

    $this->post(route('favorites.store', $product))->assertRedirect(route('login'));
    $this->post(route('reviews.store', $product), ['rating' => 5, 'comment' => '良いです'])->assertRedirect(route('login'));
});

test('user can write and update one review per product', function () {
    $user = User::factory()->create();
    $product = createStorefrontProduct($this->category);

    $this->actingAs($user)->post(route('reviews.store', $product), ['rating' => 5, 'comment' => '書きやすいです'])->assertRedirect();
    $this->actingAs($user)->post(route('reviews.store', $product), ['rating' => 4, 'comment' => '長く使えそうです'])->assertRedirect();

    expect($product->reviews()->count())->toBe(1)
        ->and($product->reviews()->first()->rating)->toBe(4)
        ->and($product->reviews()->first()->comment)->toBe('長く使えそうです');
});

test('cart item quantity can be updated and item can be removed', function () {
    $product = createStorefrontProduct($this->category, ['stock' => 8]);

    $this->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 2])
        ->assertSessionHas('cart.'.$product->id, 2);
    $this->patch(route('cart.update', $product), ['quantity' => 4])
        ->assertSessionHas('cart.'.$product->id, 4);
    $this->delete(route('cart.destroy', $product))
        ->assertSessionMissing('cart.'.$product->id);
});

test('cart rejects quantities greater than stock', function () {
    $product = createStorefrontProduct($this->category, ['stock' => 2]);

    $this->from(route('products.show', $product))
        ->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 3])
        ->assertRedirect(route('products.show', $product))
        ->assertSessionHasErrors('quantity');
});

test('placing an order reduces stock and empties the cart', function () {
    $user = User::factory()->create();
    $product = createStorefrontProduct($this->category, ['stock' => 7, 'price' => 450]);

    $response = $this->actingAs($user)
        ->withSession(['cart' => [$product->id => 3]])
        ->post(route('orders.store'));

    $response->assertOk()->assertSessionMissing('cart');
    expect($product->fresh()->stock)->toBe(4)
        ->and($user->orders()->first()->total_price)->toBe(1350);
});

test('empty cart cannot create an order', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('orders.store'))
        ->assertRedirect(route('cart.index'));

    expect(Order::count())->toBe(0);
});

test('users cannot view another users order', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $order = Order::create(['user_id' => $owner->id, 'total_price' => 1000]);

    $this->actingAs($otherUser)->get(route('orders.show', $order))->assertForbidden();
});

function createStorefrontProduct(Category $category, array $attributes = []): Product
{
    return Product::create(array_merge([
        'category_id' => $category->id,
        'name' => '試し書きペン',
        'price' => 300,
        'description' => '毎日使いやすい文房具です。',
        'image' => 'images/products/pen.png',
        'stock' => 10,
    ], $attributes));
}
