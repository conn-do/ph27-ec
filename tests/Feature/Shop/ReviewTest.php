<?php

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Tests\TestCase;

/**
 * その人が その商品を 買ったことに する。
 */
function givePurchaseHistory(User $user, Product $product): Order
{
    $order = Order::factory()->for($user)->create();

    OrderDetail::factory()->for($order)->forProduct($product)->create();

    return $order;
}

test('someone who bought the product can write a review', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $product = Product::factory()->create();

    givePurchaseHistory($user, $product);

    $this->actingAs($user)
        ->post(route('reviews.store', $product), [
            'rating' => 5,
            'comment' => 'とても かきやすい！',
        ])
        ->assertRedirect(route('products.show', $product));

    $this->assertDatabaseHas('reviews', [
        'user_id' => $user->id,
        'product_id' => $product->id,
        'rating' => 5,
        'comment' => 'とても かきやすい！',
    ]);
});

test('someone who has not bought it cannot write a review', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)
        ->post(route('reviews.store', $product), ['rating' => 5])
        ->assertSessionHasErrors('rating');

    expect(Review::count())->toBe(0);
});

test('guests cannot write a review', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create();

    $this->post(route('reviews.store', $product), ['rating' => 5])
        ->assertRedirect(route('login'));
});

test('writing a second review updates the first one', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $product = Product::factory()->create();

    givePurchaseHistory($user, $product);

    $this->actingAs($user)->post(route('reviews.store', $product), ['rating' => 3]);
    $this->actingAs($user)->post(route('reviews.store', $product), ['rating' => 5]);

    expect(Review::where('product_id', $product->id)->count())->toBe(1)
        ->and(Review::firstOrFail()->rating)->toBe(5);
});

test('the rating has to be between 1 and 5', function (int $rating) {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $product = Product::factory()->create();

    givePurchaseHistory($user, $product);

    $this->actingAs($user)
        ->post(route('reviews.store', $product), ['rating' => $rating])
        ->assertSessionHasErrors('rating');
})->with([
    'zero' => 0,
    'six' => 6,
    'negative' => -1,
]);

test('a comment longer than 300 characters is rejected', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $product = Product::factory()->create();

    givePurchaseHistory($user, $product);

    $this->actingAs($user)
        ->post(route('reviews.store', $product), [
            'rating' => 5,
            'comment' => str_repeat('あ', 301),
        ])
        ->assertSessionHasErrors('comment');
});

test('reviews are shown on the product page', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create();
    $author = User::factory()->create(['name' => 'ひかる']);

    Review::factory()->for($product)->for($author)->create([
        'rating' => 5,
        'comment' => 'もようが かっこいい！',
    ]);

    $this->get(route('products.show', $product))
        ->assertSuccessful()
        ->assertSee('もようが かっこいい！')
        ->assertSee('ひかる');
});

test('can delete my own review', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $product = Product::factory()->create();

    Review::factory()->for($product)->for($user)->create();

    $this->actingAs($user)
        ->delete(route('reviews.destroy', $product))
        ->assertRedirect(route('products.show', $product));

    expect(Review::count())->toBe(0);
});

test('cannot delete someone else review', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create();

    Review::factory()->for($product)->for(User::factory())->create();

    $this->actingAs(User::factory()->create())
        ->delete(route('reviews.destroy', $product));

    expect(Review::count())->toBe(1);
});
