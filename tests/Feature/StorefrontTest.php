<?php

use App\Models\Category;
use App\Models\News;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    $this->seed(DatabaseSeeder::class);
});

test('the seeded shop renders its catalog search categories and product pages', function () {
    $product = Product::where('name', 'すごいペン')->firstOrFail();
    $this->get('/')->assertOk()->assertSee('いつもの机に、')->assertSee($product->name);
    $this->get('/search?keyword='.urlencode('すごいペン'))->assertOk()->assertSee('すごいペン')->assertDontSee('きれいなノート');
    $this->get('/search?keyword=nonexistent')->assertOk()->assertSee('商品が見つかりませんでした');
    $this->get('/categories/notebook')->assertOk()->assertSee('きれいなノート')->assertDontSee('すごいペン');
    $this->get('/products/'.$product->id)->assertOk()->assertSee('カートに入れる')->assertSee('name="_token"', false);
    $this->get('/cart')->assertOk()->assertSee('カートは空です');
});

test('seeders reproduce a usable catalog without duplicate records', function () {
    $counts = [Product::count(), Category::count(), News::count(), User::count(), Order::count()];
    $this->seed(DatabaseSeeder::class);
    expect([Product::count(), Category::count(), News::count(), User::count(), Order::count()])->toBe($counts);
    expect(Product::where('stock', '>', 0)->count())->toBe(3);
    Storage::disk('public')->assertExists('images/products/note.png');
});

test('cart quantities can be updated and products removed independently', function () {
    $products = Product::all();
    $first = $products[0];
    $second = $products[1];
    $this->post('/cart', ['productId' => $first->id, 'quantity' => 2])->assertRedirect('/cart')->assertSessionHas('cart.'.$first->id, 2);
    $this->post('/cart', ['productId' => $second->id, 'quantity' => 1])->assertRedirect('/cart');
    $this->post('/cart', ['productId' => $first->id, 'quantity' => 3])->assertSessionHas('cart.'.$first->id, 3);
    $this->get('/cart')->assertOk()->assertViewHas('totalPrice', $first->price * 3 + $second->price);
    $this->delete('/cart/'.$first->id)->assertRedirect('/cart')->assertSessionMissing('cart.'.$first->id)->assertSessionHas('cart.'.$second->id, 1);
    $this->delete('/cart/clear')->assertRedirect('/cart')->assertSessionMissing('cart');
});

test('cart rejects invalid quantities and missing products', function (int $quantity) {
    $product = Product::firstOrFail();
    $this->post('/cart', ['productId' => $product->id, 'quantity' => $quantity])->assertSessionHasErrors('quantity');
    $this->post('/cart', ['productId' => 99999, 'quantity' => 1])->assertSessionHasErrors('productId');
})->with([0, -1, 11]);

test('cart rejects quantities exceeding stock and tolerates deleted products', function () {
    $product = Product::firstOrFail();
    $product->update(['stock' => 1]);
    $this->post('/cart', ['productId' => $product->id, 'quantity' => 2])->assertSessionHasErrors('quantity');
    $this->withSession(['cart' => [99999 => 2]])->get('/cart')->assertOk()->assertSee('カートは空です');
});

test('checkout creates order details reduces stock and clears the cart', function () {
    $user = User::factory()->create();
    $product = Product::firstOrFail();
    $this->actingAs($user)->withSession(['cart' => [$product->id => 2]])->post('/orders')->assertOk()->assertSessionMissing('cart');
    $order = $user->orders()->firstOrFail();
    expect($order->total_price)->toBe($product->price * 2);
    expect($order->details()->firstOrFail()->quantity)->toBe(2);
    expect($product->fresh()->stock)->toBe(28);
    $this->get('/orders/'.$order->id)->assertOk();
});

test('checkout rejects empty carts and rolls back unavailable stock', function () {
    $user = User::factory()->create();
    $product = Product::firstOrFail();
    $product->update(['stock' => 1]);
    $this->actingAs($user)->post('/orders')->assertSessionHasErrors('cart');
    $this->withSession(['cart' => [$product->id => 2]])->post('/orders')->assertSessionHasErrors('cart')->assertSessionHas('cart.'.$product->id, 2);
    expect($user->orders()->count())->toBe(0);
    expect($product->fresh()->stock)->toBe(1);
});

test('orders are private and checkout requires login', function () {
    $this->post('/orders')->assertRedirect(route('login'));
    $this->actingAs(User::factory()->create())->get('/orders/'.Order::firstOrFail()->id)->assertForbidden();
});

test('account forms include csrf fields and accept valid credentials', function () {
    $this->get('/login')->assertOk()->assertSee('name="_token"', false);
    $this->get('/register')->assertOk()->assertSee('name="_token"', false);
    $this->post('/login', ['email' => 'test@example.com', 'password' => 'password'])->assertRedirect();
    $this->assertAuthenticated();
});
