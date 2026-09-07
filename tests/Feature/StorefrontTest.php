<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

function createStoreProduct(Category $category, string $name = 'テストペン', int $price = 300): Product
{
    return Product::query()->create([
        'category_id' => $category->id,
        'name' => $name,
        'price' => $price,
        'description' => 'テスト用の商品です。',
        'image' => 'images/products/pen.png',
    ]);
}

test('home page filters products by category and keyword', function () {
    $writing = Category::query()->create(['name' => '書く', 'slug' => 'writing']);
    $paper = Category::query()->create(['name' => 'ノート・紙', 'slug' => 'paper']);
    $pen = createStoreProduct($writing, '青いペン');
    $notebook = createStoreProduct($paper, '方眼ノート');

    $response = $this->get(route('home', ['category' => 'writing', 'keyword' => '青い']));

    $response->assertOk();
    $response->assertSee($pen->name);
    $response->assertDontSee($notebook->name);
});

test('seeded catalog images use public asset URLs', function () {
    $category = Category::query()->create(['name' => '書く', 'slug' => 'writing']);
    $product = createStoreProduct($category);

    expect($product->imageUrl())->toContain('/images/products/pen.png');
});

test('customer can update a cart item quantity', function () {
    $category = Category::query()->create(['name' => '書く', 'slug' => 'writing']);
    $product = createStoreProduct($category);

    $this->post(route('cart.store'), [
        'product_id' => $product->id,
        'quantity' => 1,
    ])->assertRedirect(route('cart.index'));

    $this->patch(route('cart.update', $product), ['quantity' => 3])
        ->assertRedirect(route('cart.index'));

    $this->get(route('cart.index'))
        ->assertOk()
        ->assertSee('3');
});

test('customer can place an order and another user cannot view it', function () {
    $category = Category::query()->create(['name' => '書く', 'slug' => 'writing']);
    $product = createStoreProduct($category, price: 450);
    $customer = User::factory()->create();
    $otherCustomer = User::factory()->create();

    $this->actingAs($customer)
        ->withSession(['cart' => [$product->id => 2]])
        ->post(route('orders.store'))
        ->assertRedirect();

    $order = Order::query()->sole();

    expect($order->user_id)->toBe($customer->id);
    expect($order->total_price)->toBe(900);
    expect($order->details)->toHaveCount(1);
    expect($order->details->sole()->quantity)->toBe(2);

    $this->actingAs($otherCustomer)
        ->get(route('orders.show', $order))
        ->assertForbidden();
});
