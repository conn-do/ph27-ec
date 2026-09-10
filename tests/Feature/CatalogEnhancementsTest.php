<?php

use App\Models\Product;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;

test('product seeder provides a fuller repeatable catalog', function () {
    $this->seed(DatabaseSeeder::class);
    $this->seed(DatabaseSeeder::class);

    $this->assertDatabaseCount('products', 12);
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('手紙を書きたくなる万年筆')
        ->assertSee('帆布のフラットペンケース');
});

test('ranking orders products by purchased quantity', function () {
    $user = User::factory()->create();
    $popular = Product::factory()->create(['name' => '人気の商品']);
    $second = Product::factory()->create(['name' => '次に人気の商品']);
    $third = Product::factory()->create(['name' => '三番目の商品']);
    $fourth = Product::factory()->create(['name' => '四番目の商品']);
    $fifth = Product::factory()->create(['name' => '五番目の商品']);
    $sixth = Product::factory()->create(['name' => '六番目の商品']);
    $order = $user->orders()->create(['total_price' => 2000]);
    foreach ([[$popular, 6], [$second, 5], [$third, 4], [$fourth, 3], [$fifth, 2], [$sixth, 1]] as [$product, $quantity]) {
        $order->details()->create(['product_id' => $product->id, 'product_name' => $product->name, 'unit_price' => 500, 'quantity' => $quantity]);
    }

    $this->get(route('products.ranking'))
        ->assertOk()
        ->assertSeeInOrder([$popular->name, $second->name, $third->name, $fourth->name, $fifth->name])
        ->assertSee('6点購入されています')
        ->assertDontSee($sixth->name);
});

test('authenticated customers can add view and remove their favorites', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $product = Product::factory()->create(['name' => '保存したノート']);
    $otherProduct = Product::factory()->create(['name' => '他の人のペン']);
    $other->favoriteProducts()->attach($otherProduct);

    $this->actingAs($user)->post(route('favorites.store', $product))->assertRedirect();
    $this->actingAs($user)->post(route('favorites.store', $product))->assertRedirect();
    $this->assertDatabaseCount('favorites', 2);
    $this->get(route('favorites.index'))
        ->assertOk()
        ->assertSee($product->name)
        ->assertDontSee($otherProduct->name)
        ->assertSee('aria-pressed="true"', false);

    $this->delete(route('favorites.destroy', $product))->assertRedirect();
    $this->assertDatabaseMissing('favorites', ['user_id' => $user->id, 'product_id' => $product->id]);
});

test('favorite pages and mutations require authentication', function () {
    $product = Product::factory()->create();

    $this->get(route('favorites.index'))->assertRedirect(route('login'));
    $this->post(route('favorites.store', $product))->assertRedirect(route('login'));
    $this->delete(route('favorites.destroy', $product))->assertRedirect(route('login'));
});

test('mypage shows account summary favorites and updates profile in place', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['name' => 'お気に入りのペン']);
    $user->favoriteProducts()->attach($product);

    $this->actingAs($user)->get(route('mypage'))
        ->assertOk()
        ->assertSee('プロフィール情報')
        ->assertSee($product->name)
        ->assertSee('value="'.$user->email.'"', false);

    $this->from(route('mypage'))->patch(route('profile.update'), [
        'name' => '山田 花子',
        'email' => 'hanako@example.com',
    ])->assertSessionHasNoErrors()->assertRedirect(route('mypage'));

    expect($user->fresh()->name)->toBe('山田 花子')
        ->and($user->fresh()->email)->toBe('hanako@example.com');
});
