<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Panel;

test('catalog searches, sorts and scopes products by category', function () {
    $category = Category::factory()->create(['name' => 'ノート']);
    $cheap = Product::factory()->for($category)->create(['name' => 'ノート A', 'price' => 100]);
    $expensive = Product::factory()->for($category)->create(['name' => 'ノート B', 'price' => 900]);
    Product::factory()->create(['name' => '検索対象外の商品']);
    $this->get(route('products.search', ['keyword' => 'ノート', 'sort' => 'price_asc']))
        ->assertOk()->assertSeeInOrder([$cheap->name, $expensive->name])->assertDontSee('検索対象外の商品')->assertSee('2 items');
    $this->get(route('categories.show', $category))->assertOk()->assertSee($cheap->name)->assertDontSee('検索対象外の商品');
    $this->get(route('products.search', ['keyword' => 'nothing matches']))->assertOk()->assertSee('商品が見つかりませんでした。');
    $this->get(route('products.search', ['keyword' => ['invalid']]))->assertSessionHasErrors('keyword');
});
test('catalog preserves filters on pagination and shows sold out state', function () {
    Product::factory()->count(11)->create(['name' => 'Notebook']);
    $response = $this->get(route('products.search', ['keyword' => 'Notebook', 'sort' => 'price_desc']));
    $response->assertOk()->assertSee('page=2')->assertSee('keyword=Notebook');
    $soldOut = Product::factory()->create(['stock' => 0]);
    $this->get(route('products.show', $soldOut))->assertOk()->assertSee('売り切れ')->assertSee('disabled', false);
});
test('cart supports cumulative addition quantity update removal and clearing', function () {
    $product = Product::factory()->create();
    $this->post(route('cart.store'), ['productId' => $product->id, 'quantity' => 2])->assertRedirect(route('cart.index'));
    $this->post(route('cart.store'), ['productId' => $product->id, 'quantity' => 3])->assertSessionHas('cart.'.$product->id, 5);
    $this->patch(route('cart.update', $product), ['quantity' => 4])->assertSessionHas('cart.'.$product->id, 4);
    $this->get(route('cart.index'))->assertOk()->assertSee('¥2,500');
    $this->delete(route('cart.destroy', $product))->assertSessionMissing('cart.'.$product->id);
    $this->post(route('cart.store'), ['productId' => $product->id, 'quantity' => 1]);
    $this->delete(route('cart.clear'))->assertSessionMissing('cart');
    $this->get('/cart/clear')->assertMethodNotAllowed();
});
test('cart validates product and quantity', function (mixed $quantity) {
    $product = Product::factory()->create();
    $this->post(route('cart.store'), ['productId' => $product->id, 'quantity' => $quantity])->assertSessionHasErrors('quantity');
    $this->assertDatabaseCount('orders', 0);
})->with([0, -1, 11, 1.5, 'invalid']);
test('cart rejects missing products and cumulative stock overflow', function () {
    $this->post(route('cart.store'), ['productId' => 999, 'quantity' => 1])->assertSessionHasErrors('productId');
    $product = Product::factory()->create(['stock' => 3]);
    $this->withSession(['cart' => [$product->id => 2]])->post(route('cart.store'), ['productId' => $product->id, 'quantity' => 2])
        ->assertSessionHasErrors('quantity')->assertSessionHas('cart.'.$product->id, 2);
    $this->patch(route('cart.update', $product), ['quantity' => 4])->assertSessionHasErrors('quantity');
});
test('deleted cart products are cleaned up without breaking the page', function () {
    $this->withSession(['cart' => [999 => 1]])->get(route('cart.index'))->assertOk()->assertSee('カートは、まだ空っぽです。')->assertSessionHas('cart', []);
});
test('public forms include csrf and login keeps the intended checkout destination', function () {
    $product = Product::factory()->create();
    $this->get(route('products.show', $product))->assertSee('name="_token"', false);
    $this->get(route('login'))->assertOk()->assertSee('name="_token"', false);
    $this->get(route('register'))->assertOk()->assertSee('name="_token"', false);
    $this->withSession(['cart' => [$product->id => 1]])->get(route('checkout'))->assertRedirect(route('login'));
    $user = User::factory()->create();
    $this->post(route('login.store'), ['email' => $user->email, 'password' => 'password'])->assertRedirect(route('checkout'));
});
test('seeders are repeatable and do not reset stock or create privileged accounts', function () {
    $this->seed(DatabaseSeeder::class);
    $product = Product::first();
    $product->update(['stock' => 2]);
    $this->seed(DatabaseSeeder::class);
    $this->assertDatabaseCount('products', 12);
    $this->assertDatabaseCount('categories', 3);
    $this->assertDatabaseCount('news', 3);
    $this->assertDatabaseCount('users', 0);
    expect($product->fresh()->stock)->toBe(2);
    $this->get(route('home'))->assertOk()->assertSee('余白');
});
test('registering the old demo email cannot grant admin access', function () {
    $this->post(route('register.store'), ['name' => 'Customer', 'email' => 'test@example.com', 'password' => 'password', 'password_confirmation' => 'password', 'is_admin' => true])->assertRedirect();
    $user = User::whereEmail('test@example.com')->firstOrFail();
    expect($user->is_admin)->toBeFalse()->and($user->canAccessPanel(Panel::make()))->toBeFalse();
    $this->get('/admin')->assertForbidden();
    $this->artisan('shop:make-admin', ['email' => $user->email])->assertSuccessful();
    expect($user->fresh()->canAccessPanel(Panel::make()))->toBeTrue();
    $this->artisan('shop:make-admin', ['email' => 'missing@example.com'])->assertFailed();
});

test('setup preserves a category table created during lessons', function () {
    $category = Category::factory()->create();
    $migration = require database_path('migrations/2026_07_07_132842_create_categories_table.php');
    $migration->up();
    $this->assertDatabaseHas('categories', ['id' => $category->id]);
});

test('seeding assigns legacy products a category without overwriting their data', function () {
    $product = Product::factory()->create(['name' => 'すごいペン', 'image' => 'images/products/pen.png', 'category_id' => null, 'price' => 777, 'stock' => 4]);
    $this->seed(DatabaseSeeder::class);
    expect($product->fresh()->category->slug)->toBe('pen');
    expect($product->fresh()->name)->toBe('すごいペン')->and($product->fresh()->price)->toBe(777)->and($product->fresh()->stock)->toBe(4);
    expect($product->fresh()->image)->toBe('images/products/catalog/01-great-pen.webp');
    $this->assertDatabaseCount('products', 12);
});
