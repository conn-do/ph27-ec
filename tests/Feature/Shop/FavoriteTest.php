<?php

use App\Models\Favorite;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

test('guests cannot use favorites', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create();

    $this->post(route('favorites.store', $product))->assertRedirect(route('login'));
    $this->get(route('favorites.index'))->assertRedirect(route('login'));
});

test('can add a product to favorites', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)
        ->from(route('products.show', $product))
        ->post(route('favorites.store', $product))
        ->assertRedirect(route('products.show', $product));

    $this->assertDatabaseHas('favorites', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);
});

test('pressing the button again removes it from favorites', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $product = Product::factory()->create();

    Favorite::factory()->for($user)->for($product)->create();

    $this->actingAs($user)
        ->from(route('products.show', $product))
        ->post(route('favorites.store', $product));

    $this->assertDatabaseMissing('favorites', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);
});

test('the same product is never favorited twice', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)->post(route('favorites.store', $product));
    $this->actingAs($user)->post(route('favorites.store', $product));
    $this->actingAs($user)->post(route('favorites.store', $product));

    expect(Favorite::where('user_id', $user->id)->count())->toBe(1);
});

test('the favorites page lists only my own favorites', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $mine = Product::factory()->create(['name' => 'ロボットふでばこ']);
    $theirs = Product::factory()->create(['name' => 'よその しょうひん']);

    Favorite::factory()->for($user)->for($mine)->create();
    Favorite::factory()->for(User::factory())->for($theirs)->create();

    $this->actingAs($user)
        ->get(route('favorites.index'))
        ->assertSuccessful()
        ->assertSee('ロボットふでばこ')
        ->assertDontSee('よその しょうひん');
});

test('shows an empty message when there are no favorites', function () {
    /** @var TestCase $this */
    $this->actingAs(User::factory()->create())
        ->get(route('favorites.index'))
        ->assertSuccessful()
        ->assertSee('まだ おきにいりが ないよ。');
});

test('can remove a favorite from the favorites page', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $product = Product::factory()->create();

    Favorite::factory()->for($user)->for($product)->create();

    $this->actingAs($user)
        ->from(route('favorites.index'))
        ->delete(route('favorites.destroy', $product))
        ->assertRedirect(route('favorites.index'));

    expect(Favorite::count())->toBe(0);
});
