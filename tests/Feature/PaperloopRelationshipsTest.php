<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\QueryException;

test('categories and products can access each other', function () {
    $category = Category::factory()->create();
    $products = Product::factory()->count(2)->for($category)->create();
    Product::factory()->create();

    expect($category->products->modelKeys())->toEqualCanonicalizing($products->modelKeys())
        ->and($products->first()->category->is($category))->toBeTrue();
});

test('users orders and order items expose their relationships', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 880]);
    $order = Order::factory()->for($user)->create(['total_price' => 1760]);
    $item = OrderItem::factory()->for($order)->for($product)->create(['quantity' => 2]);

    expect($user->orders->sole()->is($order))->toBeTrue()
        ->and($order->user->is($user))->toBeTrue()
        ->and($order->items->sole()->is($item))->toBeTrue()
        ->and($item->order->is($order))->toBeTrue()
        ->and($item->product->is($product))->toBeTrue()
        ->and($item->product_id)->toBe($product->id)
        ->and($item->product_name)->toBe($product->name)
        ->and($item->price)->toBe(880);
});

test('favorites work in both directions with timestamps and can be detached', function () {
    $users = User::factory()->count(2)->create();
    $products = Product::factory()->count(2)->create();
    $user = $users->first();
    $product = $products->first();

    $user->favorites()->attach($products->modelKeys());
    $product->favoritedBy()->attach($users->last());

    expect($user->favorites->modelKeys())->toEqualCanonicalizing($products->modelKeys())
        ->and($product->favoritedBy->modelKeys())->toEqualCanonicalizing($users->modelKeys())
        ->and($user->favorites->first()->pivot->created_at)->not->toBeNull()
        ->and($user->favorites->first()->pivot->updated_at)->not->toBeNull();

    $user->favorites()->detach($product);

    expect($user->fresh()->favorites->sole()->is($products->last()))->toBeTrue()
        ->and($product->fresh()->favoritedBy->sole()->is($users->last()))->toBeTrue();

    $this->assertDatabaseCount('favorites', 2);
});

test('categories with products cannot be deleted', function () {
    $product = Product::factory()->create();
    $category = $product->category;

    expect(fn () => $category->delete())->toThrow(QueryException::class);

    $this->assertModelExists($category);
    $this->assertModelExists($product);

    $product->delete();
    $category->delete();

    $this->assertModelMissing($category);
});

test('changing a product does not overwrite purchase snapshots', function () {
    $product = Product::factory()->create(['name' => 'Grid Note A5', 'price' => 880]);
    $order = Order::factory()->create(['total_price' => 1760]);
    $item = OrderItem::factory()->for($order)->for($product)->create(['quantity' => 2]);

    $product->update(['name' => 'Grid Note A5 Renewed', 'price' => 990]);
    $item->refresh();

    expect($item->product_name)->toBe('Grid Note A5')
        ->and($item->price)->toBe(880)
        ->and($item->quantity)->toBe(2)
        ->and($item->product->name)->toBe('Grid Note A5 Renewed')
        ->and($order->fresh()->total_price)->toBe(1760);
});

test('deleting a product preserves order history and removes its favorites', function () {
    $product = Product::factory()->create(['name' => 'Brass Clip Set', 'price' => 770]);
    $order = Order::factory()->create(['total_price' => 770]);
    $item = OrderItem::factory()->for($order)->for($product)->create();
    $order->user->favorites()->attach($product);

    $product->delete();
    $item->refresh();

    $this->assertModelMissing($product);
    $this->assertModelExists($order);
    $this->assertModelExists($item);
    $this->assertDatabaseCount('favorites', 0);

    expect($item->product_id)->toBeNull()
        ->and($item->product)->toBeNull()
        ->and($item->product_name)->toBe('Brass Clip Set')
        ->and($item->price)->toBe(770)
        ->and($order->fresh()->total_price)->toBe(770);
});

test('the existing account deletion flow works with orders and favorites while retaining order history', function () {
    $user = User::factory()->create();
    $chirp = $user->chirps()->create(['message' => '授業のChirp']);
    $product = Product::factory()->create(['price' => 880]);
    $order = Order::factory()->for($user)->create([
        'total_price' => 880,
        'customer_name' => '紙野 花',
        'postal_code' => '100-0001',
        'address' => '東京都千代田区千代田1-1',
    ]);
    $item = OrderItem::factory()->for($order)->for($product)->create();
    $user->favorites()->attach($product);

    $this->actingAs($user)->delete(route('profile.destroy'), ['password' => 'password'])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('home'));

    $this->assertGuest();
    $this->assertModelMissing($user);
    $this->assertModelMissing($chirp);
    $this->assertModelExists($product);
    $this->assertModelExists($order);
    $this->assertModelExists($item);
    $this->assertDatabaseCount('favorites', 0);

    $order->refresh();

    expect($order->user_id)->toBeNull()
        ->and($order->user)->toBeNull()
        ->and($order->total_price)->toBe(880)
        ->and($order->customer_name)->toBe('紙野 花')
        ->and($order->postal_code)->toBe('100-0001')
        ->and($order->address)->toBe('東京都千代田区千代田1-1')
        ->and($order->items->sole()->is($item))->toBeTrue();
});

test('deleting an order removes only its order items', function () {
    $item = OrderItem::factory()->create();
    $order = $item->order;
    $user = $order->user;
    $product = $item->product;

    $order->delete();

    $this->assertModelMissing($order);
    $this->assertModelMissing($item);
    $this->assertModelExists($product);
    $this->assertModelExists($user);
});
