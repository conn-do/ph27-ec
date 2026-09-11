<?php

use App\Models\Product;
use App\Models\Review;
use App\Models\User;

test('ゲストはレビューを投稿できない', function () {
    $product = Product::factory()->create();

    $this->post("/products/{$product->id}/reviews", [
        'rating' => 5,
        'comment' => 'とても良い商品です',
    ])->assertRedirect('/login');

    expect(Review::count())->toBe(0);
});

test('ログイン中のユーザーはレビューを投稿できる', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)->post("/products/{$product->id}/reviews", [
        'rating' => 4,
        'comment' => 'なかなか良かったです',
    ])->assertRedirect("/products/{$product->id}");

    $review = Review::first();
    expect($review->user_id)->toBe($user->id);
    expect($review->product_id)->toBe($product->id);
    expect($review->rating)->toBe(4);
    expect($review->comment)->toBe('なかなか良かったです');
});

test('評価は1から5の範囲でなければならない', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)->post("/products/{$product->id}/reviews", [
        'rating' => 6,
    ])->assertSessionHasErrors('rating');

    $this->actingAs($user)->post("/products/{$product->id}/reviews", [
        'rating' => 0,
    ])->assertSessionHasErrors('rating');

    expect(Review::count())->toBe(0);
});

test('同じ商品に再度投稿すると既存のレビューが更新される', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)->post("/products/{$product->id}/reviews", [
        'rating' => 3,
        'comment' => '最初のレビュー',
    ]);

    $this->actingAs($user)->post("/products/{$product->id}/reviews", [
        'rating' => 5,
        'comment' => '更新後のレビュー',
    ]);

    expect(Review::count())->toBe(1);
    expect(Review::first()->rating)->toBe(5);
    expect(Review::first()->comment)->toBe('更新後のレビュー');
});

test('商品詳細ページに平均評価とレビュー一覧が表示される', function () {
    $product = Product::factory()->create();
    Review::factory()->create(['product_id' => $product->id, 'rating' => 4]);
    Review::factory()->create(['product_id' => $product->id, 'rating' => 2]);

    $response = $this->get("/products/{$product->id}");

    $response->assertOk();
    $response->assertSee('3'); // (4+2)/2 = 3.0
});

test('自分のレビューは削除できる', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    $review = Review::factory()->create(['user_id' => $user->id, 'product_id' => $product->id]);

    $this->actingAs($user)
        ->post("/reviews/{$review->id}/delete")
        ->assertRedirect("/products/{$product->id}");

    expect(Review::find($review->id))->toBeNull();
});

test('他人のレビューは削除できない', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $product = Product::factory()->create();
    $review = Review::factory()->create(['user_id' => $owner->id, 'product_id' => $product->id]);

    $this->actingAs($other)
        ->post("/reviews/{$review->id}/delete")
        ->assertForbidden();

    expect(Review::find($review->id))->not->toBeNull();
});
