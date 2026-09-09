<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

test('restyled catalogue pages retain cart forms stock and authentication links', function () {
    $category = Category::query()->create(['name' => '筆記用具', 'slug' => 'pen']);
    $product = createStoreProduct($category);

    foreach ([route('home'), route('categories.show', $category), route('products.show', $product)] as $url) {
        $this->get($url)->assertOk()
            ->assertSee($product->name)
            ->assertSee('カートに入れる')
            ->assertSee('在庫あり')
            ->assertSee('action="'.route('cart.store').'"', false)
            ->assertSee('name="product_id"', false)
            ->assertSee('name="quantity"', false)
            ->assertSee('name="_token"', false)
            ->assertSee(route('login'))
            ->assertSee(route('register'));
    }
});
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Support\Facades\Storage;

function createStoreProduct(Category $category, string $name = 'テストペン', int $price = 300, int $stock = 10): Product
{
    return Product::query()->create([
        'category_id' => $category->id,
        'name' => $name,
        'price' => $price,
        'description' => 'テスト用の商品です。',
        'image' => 'images/products/pen.png',
        'stock' => $stock,
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
        ->assertRedirect()
        ->assertSessionMissing('cart');

    $order = Order::query()->sole();

    expect($order->user_id)->toBe($customer->id);
    expect($order->total_price)->toBe(900);
    expect($order->details)->toHaveCount(1);
    expect($order->details->sole()->quantity)->toBe(2);
    expect($product->fresh()->stock)->toBe(8);

    $this->actingAs($otherCustomer)
        ->get(route('orders.show', $order))
        ->assertForbidden();
});

test('checkout rolls back all stock orders and details when a later item is short', function () {
    $category = Category::query()->create(['name' => 'Writing', 'slug' => 'writing']);
    $first = createStoreProduct($category, 'First', stock: 5);
    $second = createStoreProduct($category, 'Second', stock: 1);
    $cart = [$first->id => 2, $second->id => 2];

    $this->actingAs(User::factory()->create())
        ->withSession(['cart' => $cart])
        ->post(route('orders.store'))
        ->assertRedirect(route('cart.index'))
        ->assertSessionHasErrors('cart')
        ->assertSessionHas('cart', $cart);

    expect($first->fresh()->stock)->toBe(5);
    expect($second->fresh()->stock)->toBe(1);
    $this->assertDatabaseCount('orders', 0);
    $this->assertDatabaseCount('order_details', 0);
});

test('checkout rejects invalid session quantities without creating orders', function (mixed $quantity) {
    $category = Category::query()->create(['name' => 'Writing', 'slug' => 'writing']);
    $product = createStoreProduct($category);

    $this->actingAs(User::factory()->create())
        ->withSession(['cart' => [$product->id => $quantity]])
        ->post(route('orders.store'))
        ->assertRedirect(route('cart.index'))
        ->assertSessionHasErrors();

    expect($product->fresh()->stock)->toBe(10);
    $this->assertDatabaseCount('orders', 0);
    $this->assertDatabaseCount('order_details', 0);
})->with([0, -1, 11, 1.5, 'invalid', null]);

test('cart add and update reject invalid quantities and preserve existing contents', function (string $verb, mixed $quantity) {
    $category = Category::query()->create(['name' => 'Writing', 'slug' => 'writing']);
    $product = createStoreProduct($category, stock: 2);
    $cart = [$product->id => 1];
    $url = $verb === 'post' ? route('cart.store') : route('cart.update', $product);

    $this->withSession(['cart' => $cart])
        ->{$verb}($url, ['product_id' => $product->id, 'quantity' => $quantity])
        ->assertSessionHasErrors('quantity')
        ->assertSessionHas('cart', $cart);

    expect($product->fresh()->stock)->toBe(2);
})->with(['post', 'patch'])->with([0, -1, 3, 11, 1.5, 'invalid']);

test('sold out products cannot be added to the cart', function () {
    $category = Category::query()->create(['name' => 'Writing', 'slug' => 'writing']);
    $product = createStoreProduct($category, stock: 0);

    $this->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 1])
        ->assertSessionHasErrors('quantity')
        ->assertSessionMissing('cart');
});

test('cart delete controls remove one item or clear the cart', function () {
    $category = Category::query()->create(['name' => 'Writing', 'slug' => 'writing']);
    $first = createStoreProduct($category, 'First');
    $second = createStoreProduct($category, 'Second');

    $this->withSession(['cart' => [$first->id => 1, $second->id => 2]])
        ->delete(route('cart.destroy', $first))
        ->assertRedirect(route('cart.index'))
        ->assertSessionHas('cart', [$second->id => 2]);

    $this->delete(route('cart.clear'))
        ->assertRedirect(route('cart.index'))
        ->assertSessionMissing('cart');
});

test('named storefront routes retain their HTTP control contracts', function () {
    $routes = app('router')->getRoutes();
    foreach ([
        'home' => 'GET',
        'products.search' => 'GET',
        'categories.show' => 'GET',
        'products.show' => 'GET',
        'news.show' => 'GET',
        'dashboard' => 'GET',
        'cart.index' => 'GET',
        'cart.store' => 'POST',
        'cart.update' => 'PATCH',
        'cart.destroy' => 'DELETE',
        'cart.clear' => 'DELETE',
        'orders.store' => 'POST',
        'orders.index' => 'GET',
        'orders.show' => 'GET',
    ] as $name => $method) {
        expect($routes->getByName($name))->not->toBeNull();
        expect($routes->getByName($name)->methods())->toContain($method);
    }
});

test('category slug and search endpoints retain their product filters', function () {
    $category = Category::query()->create(['name' => 'Writing', 'slug' => 'writing']);
    $product = createStoreProduct($category, 'Blue pen');

    expect(route('categories.show', $category))->toEndWith('/categories/writing');
    $this->get(route('categories.show', $category))
        ->assertOk()
        ->assertViewHas('category', fn (Category $value): bool => $value->is($category));
    $this->get(route('products.search', ['keyword' => 'Blue', 'category' => 'writing']))
        ->assertOk()
        ->assertViewHas('products', fn ($products): bool => $products->modelKeys() === [$product->id]);
});

test('checkout rejects missing products without losing the cart', function () {
    $cart = [999999 => 1];
    $this->actingAs(User::factory()->create())
        ->withSession(['cart' => $cart])
        ->post(route('orders.store'))
        ->assertRedirect(route('cart.index'))
        ->assertSessionHasErrors('cart')
        ->assertSessionHas('cart', $cart);
    $this->assertDatabaseCount('orders', 0);
});

test('product seeding preserves existing records and inventory on repeated runs', function () {
    Storage::fake('public');
    $this->seed(CategorySeeder::class);
    $category = Category::query()->where('slug', 'writing')->firstOrFail();
    $existing = createStoreProduct($category, 'Existing product', stock: 4);
    $this->seed(ProductSeeder::class);
    $seeded = Product::query()->where('name', '!=', $existing->name)->firstOrFail();
    $seeded->update(['stock' => 0, 'price' => 123]);
    $count = Product::query()->count();

    $this->seed(ProductSeeder::class);

    expect(Product::query()->count())->toBe($count);
    expect($existing->fresh()->stock)->toBe(4);
    expect($seeded->fresh()->stock)->toBe(0);
    expect($seeded->fresh()->price)->toBe(123);
    expect(Product::query()->where('category_id', '!=', $category->id)->count())->toBe(0);
    expect($category->fresh()->name)->toBe('筆記用具');
});
