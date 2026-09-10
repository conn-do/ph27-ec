<?php

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\ProductSeeder;

beforeEach(function () {
    /** @var \Tests\TestCase $this */
    $this->seed(ProductSeeder::class);
});

test('products page shows the teacher style catalog', function () {
    /** @var \Tests\TestCase $this */
    $this->get(route('products.index'))
        ->assertOk()
        ->assertSee('商品一覧')
        ->assertSee('すごいペン')
        ->assertSee('きれいなノート')
        ->assertSee('よく消える鉛筆');
});

test('customers can view a product detail page', function () {
    /** @var \Tests\TestCase $this */
    $product = Product::query()->where('name', 'すごいペン')->firstOrFail();

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertSee($product->name)
        ->assertSee($product->description)
        ->assertSee('カートに入れる');
});

test('customers can add a product to the cart', function () {
    /** @var \Tests\TestCase $this */
    $product = Product::query()->where('name', 'すごいペン')->firstOrFail();

    $this->post(route('cart.store'), [
        'productId' => $product->id,
        'quantity' => 2,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('cart.index'));

    expect(session('cart'))->toBe([
        $product->id => 2,
    ]);
});

test('cart page calculates the total price', function () {
    /** @var \Tests\TestCase $this */
    $pen = Product::query()->where('name', 'すごいペン')->firstOrFail();
    $note = Product::query()->where('name', 'きれいなノート')->firstOrFail();

    session(['cart' => [
        $pen->id => 1,
        $note->id => 1,
    ]]);

    $this->get(route('cart.index'))
        ->assertOk()
        ->assertSee('すごいペン')
        ->assertSee('きれいなノート')
        ->assertSee('合計')
        ->assertSee('¥1,414');
});

test('customers can clear the cart with a delete request', function () {
    /** @var \Tests\TestCase $this */
    $product = Product::query()->firstOrFail();
    session(['cart' => [$product->id => 1]]);

    $this->delete(route('cart.clear'))
        ->assertRedirect(route('cart.index'))
        ->assertSessionHas('message', 'カートを空にしました。');

    expect(session()->has('cart'))->toBeFalse();
});

test('cart quantities can be updated and items can be removed', function () {
    $product = Product::query()->firstOrFail();
    session(['cart' => [$product->id => 1]]);

    $this->patch(route('cart.update', $product), ['quantity' => 3])
        ->assertRedirect(route('cart.index'));

    expect(session("cart.{$product->id}"))->toBe(3);

    $this->delete(route('cart.destroy', $product))
        ->assertRedirect(route('cart.index'));

    expect(session('cart'))->toBe([]);
});

test('checkout requires login', function () {
    /** @var \Tests\TestCase $this */
    $this->post(route('orders.store'))
        ->assertRedirect(route('login'));
});

test('checkout creates an order and clears the cart', function () {
    /** @var \Tests\TestCase $this */
    $user = User::factory()->create();
    $pen = Product::query()->where('name', 'すごいペン')->firstOrFail();
    $note = Product::query()->where('name', 'きれいなノート')->firstOrFail();
    $pencil = Product::query()->where('name', 'よく消える鉛筆')->firstOrFail();

    session(['cart' => [
        $pen->id => 1,
        $note->id => 1,
        $pencil->id => 1,
    ]]);

    $this->actingAs($user)
        ->post(route('orders.store'))
        ->assertRedirect(route('orders.complete', 1))
        ->assertSessionHas('message', '注文が完了しました！');

    $order = Order::query()->firstOrFail();

    expect($order->total_price)->toBe(2184)
        ->and($order->user_id)->toBe($user->id)
        ->and(OrderDetail::query()->count())->toBe(3)
        ->and(session()->has('cart'))->toBeFalse();
});

test('checkout rejects an empty cart', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('cart.index'))
        ->post(route('orders.store'))
        ->assertRedirect(route('cart.index'))
        ->assertSessionHasErrors('cart');

    expect(Order::query()->count())->toBe(0);
});

test('customers cannot view another customers completed order', function () {
    $owner = User::factory()->create();
    $otherCustomer = User::factory()->create();
    $order = Order::query()->create(['total_price' => 100, 'user_id' => $owner->id]);

    $this->actingAs($otherCustomer)
        ->get(route('orders.complete', $order))
        ->assertForbidden();
});

test('news content is escaped', function () {
    $news = \App\Models\News::query()->create([
        'title' => '安全なお知らせ',
        'content' => '<script>alert("xss")</script>',
    ]);

    $this->get(route('news.show', $news))
        ->assertOk()
        ->assertSee('&lt;script&gt;', false)
        ->assertDontSee('<script>', false);
});
