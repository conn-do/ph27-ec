<?php

use App\Models\Category;
use App\Models\News;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->withoutVite();
});

function createProduct(array $attributes = []): Product
{
    $category = $attributes['category'] ?? Category::create([
        'name' => '筆記用具',
        'slug' => 'pen',
    ]);

    unset($attributes['category']);

    return Product::create(array_merge([
        'name' => 'すごいペン',
        'price' => 300,
        'description' => 'とてもすごいペンです。',
        'image' => 'images/products/pen.png',
        'category_id' => $category->id,
        'stock' => 10,
    ], $attributes));
}

test('storefront pages show products categories and search results', function () {
    /** @var TestCase $this */
    $product = createProduct();
    News::create([
        'title' => '新商品のお知らせ',
        'content' => '新しい文房具が入荷しました。',
    ]);

    $this->get('/')
        ->assertOk()
        ->assertSee('すごい文房具サイト')
        ->assertSee('筆記用具')
        ->assertSee($product->name);

    $this->get('/search?keyword='.urlencode('ペン'))
        ->assertOk()
        ->assertSee('筆記用具')
        ->assertSee($product->name);

    $this->get('/categories/pen')
        ->assertOk()
        ->assertSee('筆記用具')
        ->assertSee($product->name);

    $this->get('/products/'.$product->id)
        ->assertOk()
        ->assertSee($product->name)
        ->assertSee('カートに入れる');
});

test('customer can add a product to cart and place an order', function () {
    /** @var TestCase $this */
    $product = createProduct(['stock' => 5]);
    $user = User::factory()->create();

    $this->post('/cart', [
        'productId' => $product->id,
        'quantity' => 2,
    ])->assertRedirect('/cart');

    $this->get('/cart')
        ->assertOk()
        ->assertSee($product->name)
        ->assertSee('600円');

    $this->actingAs($user)
        ->withSession(['cart' => [$product->id => 2]])
        ->post('/orders')
        ->assertOk()
        ->assertSee('注文ID');

    expect($product->refresh()->stock)->toBe(3);
    expect(Order::query()->where('user_id', $user->id)->where('total_price', 600)->exists())->toBeTrue();
});

test('customer cannot order more than available stock', function () {
    /** @var TestCase $this */
    $product = createProduct(['stock' => 1]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withSession(['cart' => [$product->id => 2]])
        ->post('/orders')
        ->assertRedirect('/cart')
        ->assertSessionHasErrors('cart');

    expect($product->refresh()->stock)->toBe(1);
    expect(Order::count())->toBe(0);
});
