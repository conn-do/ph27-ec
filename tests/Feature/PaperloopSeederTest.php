<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Support\Facades\Hash;

test('database seeding creates the paperloop catalog and the existing test user', function () {
    $this->seed();

    $this->assertDatabaseCount('categories', 5);
    $this->assertDatabaseCount('products', 15);
    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseCount('orders', 0);
    $this->assertDatabaseCount('order_items', 0);
    $this->assertDatabaseCount('favorites', 0);

    expect(Category::orderBy('id')->pluck('name')->all())
        ->toBe(['Writing', 'Notebook', 'Desk', 'Storage', 'Tools']);

    foreach (Category::withCount('products')->get() as $category) {
        expect($category->slug)->toBe(strtolower($category->name))
            ->and($category->description)->not->toBeEmpty()
            ->and($category->products_count)->toBe(3);
    }

    foreach (Product::with('category')->get() as $product) {
        expect($product->name)->not->toBeEmpty()
            ->and($product->description)->not->toBeEmpty()
            ->and($product->price)->toBeInt()->toBeGreaterThan(0)
            ->and($product->stock)->toBeInt()->toBeGreaterThanOrEqual(0)
            ->and($product->category)->toBeInstanceOf(Category::class)
            ->and($product->image)->toBe('/images/products/'.$product->slug.'.jpg')
            ->and($product->is_featured)->toBeBool()
            ->and($product->is_active)->toBeTrue();
    }

    $notebook = Product::where('slug', 'grid-note-a5')->firstOrFail();
    $testUser = User::where('email', 'test@example.com')->firstOrFail();

    expect($notebook->name)->toBe('グリッドノート A5')
        ->and($notebook->price)->toBe(880)
        ->and($notebook->stock)->toBe(40)
        ->and($notebook->category->slug)->toBe('notebook')
        ->and(Product::where('is_featured', true)->count())->toBe(6)
        ->and($testUser->name)->toBe('Test User')
        ->and(Hash::check('password', $testUser->password))->toBeTrue()
        ->and($testUser->email_verified_at)->not->toBeNull();
});

test('running the database seeder again leaves all existing catalog and user attributes unchanged', function () {
    $this->seed();

    $categories = Category::orderBy('id')->get()->toArray();
    $products = Product::orderBy('id')->get()->toArray();
    $testUser = User::where('email', 'test@example.com')->firstOrFail()->getAttributes();

    $this->travel(1)->days();
    $this->seed();

    expect(Category::orderBy('id')->get()->toArray())->toBe($categories)
        ->and(Product::orderBy('id')->get()->toArray())->toBe($products)
        ->and(User::where('email', 'test@example.com')->firstOrFail()->getAttributes())->toBe($testUser);

    $this->assertDatabaseCount('users', 1);
});

test('seeding preserves existing credentials and catalog values other than localized catalog copy', function () {
    $user = User::factory()->unverified()->create([
        'email' => 'test@example.com',
        'name' => 'Existing Student',
        'password' => 'existing-password',
    ]);
    $password = $user->password;
    $otherUser = User::factory()->create();
    $chirp = $user->chirps()->create(['message' => '既存の授業データ']);
    Category::factory()->create();
    $category = Category::factory()->create(['slug' => 'notebook', 'name' => 'My Notebook Category']);
    $product = Product::factory()->for($category)->create([
        'slug' => 'grid-note-a5',
        'name' => 'My Grid Note',
        'price' => 990,
        'stock' => 7,
        'is_active' => false,
    ]);

    $this->seed();
    $this->seed();

    $user->refresh();
    $product->refresh();

    expect($user->name)->toBe('Existing Student')
        ->and($user->password)->toBe($password)
        ->and(Hash::check('existing-password', $user->password))->toBeTrue()
        ->and($user->email_verified_at)->toBeNull()
        ->and($product->name)->toBe('グリッドノート A5')
        ->and($product->description)->toBe('淡いグレーの5mm方眼と、目にやさしいクリーム色の紙を使ったA5ノート。糸かがり製本で開きやすく、図も文章も自由に残せます。')
        ->and($product->price)->toBe(990)
        ->and($product->stock)->toBe(7)
        ->and($product->is_active)->toBeFalse()
        ->and($product->category_id)->toBe($category->id)
        ->and($category->fresh()->name)->toBe('My Notebook Category')
        ->and(Product::where('slug', 'soft-cover-journal')->firstOrFail()->category_id)->toBe($category->id);

    $this->assertModelExists($chirp);
    $this->assertModelExists($otherUser);
    $this->assertDatabaseCount('users', 2);
    $this->assertDatabaseCount('categories', 6);
    $this->assertDatabaseCount('products', 15);
});

test('category and product seeders can be run individually in dependency order', function () {
    $this->seed(CategorySeeder::class);
    $this->seed(ProductSeeder::class);
    $this->seed(CategorySeeder::class);
    $this->seed(ProductSeeder::class);

    $this->assertDatabaseCount('categories', 5);
    $this->assertDatabaseCount('products', 15);
    $this->assertDatabaseCount('users', 0);
});
