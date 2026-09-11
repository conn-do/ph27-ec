<?php

use App\Models\Favorite;
use App\Models\Product;
use App\Models\User;

test('ゲストはお気に入りに追加できない', function () {
    $product = Product::factory()->create();

    $this->post("/products/{$product->id}/favorite")->assertRedirect('/login');

    expect(Favorite::count())->toBe(0);
});

test('ログイン中のユーザーはお気に入りに追加できる', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)
        ->post("/products/{$product->id}/favorite")
        ->assertRedirect();

    expect(Favorite::where('user_id', $user->id)->where('product_id', $product->id)->exists())->toBeTrue();
});

test('同じ商品を二重にお気に入り登録しても1件だけになる', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)->post("/products/{$product->id}/favorite");
    $this->actingAs($user)->post("/products/{$product->id}/favorite");

    expect(Favorite::where('user_id', $user->id)->where('product_id', $product->id)->count())->toBe(1);
});

test('お気に入りから削除できる', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    Favorite::factory()->create(['user_id' => $user->id, 'product_id' => $product->id]);

    $this->actingAs($user)
        ->post("/products/{$product->id}/unfavorite")
        ->assertRedirect();

    expect(Favorite::where('user_id', $user->id)->where('product_id', $product->id)->exists())->toBeFalse();
});

test('お気に入り一覧には自分が登録した商品だけが表示される', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    $myProduct = Product::factory()->create(['name' => '自分のお気に入り']);
    $othersProduct = Product::factory()->create(['name' => '他人のお気に入り']);

    Favorite::factory()->create(['user_id' => $user->id, 'product_id' => $myProduct->id]);
    Favorite::factory()->create(['user_id' => $other->id, 'product_id' => $othersProduct->id]);

    $response = $this->actingAs($user)->get('/favorites');

    $response->assertOk();
    $response->assertSee('自分のお気に入り');
    $response->assertDontSee('他人のお気に入り');
});

test('商品詳細ページにお気に入り状態が反映される', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    Favorite::factory()->create(['user_id' => $user->id, 'product_id' => $product->id]);

    $response = $this->actingAs($user)->get("/products/{$product->id}");

    $response->assertOk();
    $response->assertSee('お気に入り済み');
});
