<?php

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\AssignProductsToWritingCategorySeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;

test('all existing products appear in the original writing category without changing stock or prices', function () {
    $writing = Category::query()->create(['name' => '筆記用具', 'slug' => 'pen']);
    $other = Category::query()->create(['name' => '収納', 'slug' => 'storage']);
    $products = collect(['すごいペン', 'きれいなノート', 'よく消える鉛筆'])->map(
        fn (string $name): Product => Product::query()->create([
            'name' => $name, 'price' => 123, 'stock' => 4,
            'description' => '文房具', 'image' => 'images/products/pen.png',
            'category_id' => $name === 'きれいなノート' ? $other->id : null,
        ]),
    );
    $this->seed(AssignProductsToWritingCategorySeeder::class);
    $this->seed(AssignProductsToWritingCategorySeeder::class);

    expect(Product::query()->count())->toBe(3);
    expect(Category::query()->count())->toBe(2);
    foreach ($products as $product) {
        expect($product->fresh()->category_id)->toBe($writing->id);
        expect($product->fresh()->stock)->toBe(4);
        expect($product->fresh()->price)->toBe(123);
    }
    $page = $this->get(route('categories.show', $writing))->assertOk();
    foreach ($products as $product) {
        $page->assertSee($product->name);
    }
    $this->get(route('home', ['category' => 'pen']))->assertOk()
        ->assertViewHas('products', fn ($items): bool => $items->count() === 3);
});

test('fresh catalog seeds put every product in writing and do not duplicate the legacy category', function () {
    $writing = Category::query()->create(['name' => '筆記用具', 'slug' => 'pen']);
    $this->seed([CategorySeeder::class, ProductSeeder::class]);
    expect(Category::query()->where('name', '筆記用具')->count())->toBe(1);
    expect(Product::query()->where('category_id', $writing->id)->count())->toBe(Product::query()->count());
    expect(Product::query()->count())->toBe(3);
});
