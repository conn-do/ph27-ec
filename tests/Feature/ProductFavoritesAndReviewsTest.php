<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

it('authenticated users can favorite a product and see it in my page', function () {
    $user = User::factory()->create();
    $category = Category::create([
        'name' => '文房具',
        'slug' => 'stationery',
    ]);

    $product = Product::unguarded(function () use ($category) {
        return Product::create([
            'name' => 'テスト商品',
            'price' => 1000,
            'description' => 'テスト説明',
            'image' => 'products/test.jpg',
            'stock' => 10,
            'category_id' => $category->id,
        ]);
    });

    $this->actingAs($user)
        ->post("/products/{$product->id}/favorite")
        ->assertRedirect();

    $this->assertDatabaseHas('favorites', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    $this->actingAs($user)
        ->get('/mypage')
        ->assertSee('お気に入り一覧')
        ->assertSee('テスト商品');
});

it('authenticated users can post and view product reviews', function () {
    $user = User::factory()->create();
    $category = Category::create([
        'name' => '文房具',
        'slug' => 'stationery-2',
    ]);

    $product = Product::unguarded(function () use ($category) {
        return Product::create([
            'name' => 'レビュー商品',
            'price' => 2000,
            'description' => 'レビュー用商品',
            'image' => 'products/review.jpg',
            'stock' => 5,
            'category_id' => $category->id,
        ]);
    });

    $this->actingAs($user)
        ->post("/products/{$product->id}/reviews", [
            'rating' => 5,
            'comment' => 'とても良いです',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('reviews', [
        'user_id' => $user->id,
        'product_id' => $product->id,
        'rating' => 5,
        'comment' => 'とても良いです',
    ]);

    $this->get("/products/{$product->id}")
        ->assertSee('とても良いです')
        ->assertSee($user->name)
        ->assertDontSee('まだレビューがありません');
});
