<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

test('paperloop migrations create all requested columns', function (string $table, array $columns) {
    expect(Schema::hasColumns($table, $columns))->toBeTrue();
})->with([
    'categories' => ['categories', ['id', 'name', 'slug', 'description', 'created_at', 'updated_at']],
    'products' => ['products', ['id', 'category_id', 'name', 'slug', 'description', 'price', 'stock', 'image', 'is_featured', 'is_active', 'created_at', 'updated_at']],
    'orders' => ['orders', ['id', 'user_id', 'total_price', 'status', 'customer_name', 'postal_code', 'address', 'created_at', 'updated_at']],
    'order items' => ['order_items', ['id', 'order_id', 'product_id', 'product_name', 'quantity', 'price', 'created_at', 'updated_at']],
    'favorites' => ['favorites', ['id', 'user_id', 'product_id', 'created_at', 'updated_at']],
]);

test('products have database defaults and integer and boolean casts', function () {
    $category = Category::factory()->create(['description' => null]);
    $id = Product::query()->toBase()->insertGetId([
        'category_id' => $category->id,
        'name' => 'Grid Note A5',
        'slug' => 'grid-note-a5',
        'description' => '毎日の記録に使える方眼ノート。',
        'price' => 880,
    ]);

    $product = Product::findOrFail($id);

    expect($product->category_id)->toBe($category->id)
        ->and($product->price)->toBe(880)
        ->and($product->stock)->toBe(0)
        ->and($product->image)->toBeNull()
        ->and($product->is_featured)->toBeFalse()
        ->and($product->is_active)->toBeTrue()
        ->and($category->fresh()->description)->toBeNull();

    $unsaved = new Product(['price' => '880', 'category_id' => (string) $category->id]);

    expect($unsaved->price)->toBe(880)
        ->and($unsaved->category_id)->toBe($category->id)
        ->and($unsaved->stock)->toBe(0)
        ->and($unsaved->is_featured)->toBeFalse()
        ->and($unsaved->is_active)->toBeTrue();

    $product->update(['is_active' => false, 'is_featured' => true, 'stock' => '12']);

    expect($product->fresh()->is_active)->toBeFalse()
        ->and($product->fresh()->is_featured)->toBeTrue()
        ->and($product->fresh()->stock)->toBe(12);
});

test('orders default to pending and cast money and quantities as integers', function () {
    $id = Order::query()->toBase()->insertGetId([
        'user_id' => null,
        'total_price' => 1760,
        'customer_name' => '紙野 花',
        'postal_code' => '001-0001',
        'address' => '北海道札幌市北区北一条1-1',
    ]);

    $order = Order::findOrFail($id);
    $item = OrderItem::factory()->for($order)->create([
        'product_id' => null,
        'product_name' => 'Grid Note A5',
        'quantity' => '2',
        'price' => '880',
    ]);

    expect($order->status)->toBe('pending')
        ->and($order->total_price)->toBe(1760)
        ->and($order->user_id)->toBeNull()
        ->and($order->postal_code)->toBe('001-0001')
        ->and((new Order)->status)->toBe('pending')
        ->and((new Order(['total_price' => '1760', 'user_id' => '1']))->total_price)->toBe(1760)
        ->and((new Order(['user_id' => '1']))->user_id)->toBe(1)
        ->and($item->fresh()->order_id)->toBe($order->id)
        ->and($item->fresh()->product_id)->toBeNull()
        ->and($item->fresh()->quantity)->toBe(2)
        ->and($item->fresh()->price)->toBe(880);
});

test('catalog slugs cannot be duplicated', function (string $model) {
    $model::factory()->create(['slug' => 'same-slug']);

    expect(fn () => $model::factory()->create(['slug' => 'same-slug']))
        ->toThrow(QueryException::class);

    expect($model::where('slug', 'same-slug')->count())->toBe(1);
})->with([
    'category' => [Category::class],
    'product' => [Product::class],
]);

test('nonexistent related records are rejected by foreign keys', function (string $model, string $foreignKey) {
    expect(fn () => $model::factory()->create([$foreignKey => 999999]))
        ->toThrow(QueryException::class);
})->with([
    'product category' => [Product::class, 'category_id'],
    'order user' => [Order::class, 'user_id'],
    'item order' => [OrderItem::class, 'order_id'],
    'item product' => [OrderItem::class, 'product_id'],
]);

test('favorites reject nonexistent users and products', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    expect(fn () => $user->favorites()->attach(999999))->toThrow(QueryException::class)
        ->and(fn () => $product->favoritedBy()->attach(999999))->toThrow(QueryException::class);

    $this->assertDatabaseCount('favorites', 0);
});

test('favorites cannot contain the same user and product twice', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    $user->favorites()->attach($product);

    expect(fn () => $user->favorites()->attach($product))->toThrow(QueryException::class);

    $this->assertDatabaseCount('favorites', 1);
});

test('the five new migrations can be rolled back and reapplied without changing classroom data', function () {
    expect(DB::connection()->getDriverName())->toBe('sqlite')
        ->and(DB::connection()->getDatabaseName())->toBe(':memory:');

    $user = User::factory()->create();
    $chirp = $user->chirps()->create(['message' => '授業の投稿を維持する']);

    $this->artisan('migrate:rollback', ['--step' => 5, '--no-interaction' => true])
        ->assertExitCode(0);

    foreach (['categories', 'products', 'orders', 'order_items', 'favorites'] as $table) {
        expect(Schema::hasTable($table))->toBeFalse();
    }

    $this->assertModelExists($user);
    $this->assertModelExists($chirp);
    expect(Schema::hasColumn('users', 'two_factor_secret'))->toBeTrue();

    $this->artisan('migrate', ['--no-interaction' => true])->assertExitCode(0);

    foreach (['categories', 'products', 'orders', 'order_items', 'favorites'] as $table) {
        expect(Schema::hasTable($table))->toBeTrue();
    }

    $this->assertModelExists($user);
    $this->assertModelExists($chirp);
});
