<?php

use App\Models\AllowanceTransaction;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\News;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\StockMovement;
use App\Models\User;
use Tests\TestCase;

/**
 * db:seed が さいごまで とおるか、データが そろっているか。
 */
beforeEach(function () {
    /** @var TestCase $this */
    $this->seed();
});

test('seeds the categories and the products', function () {
    expect(Category::count())->toBe(7)
        ->and(Product::count())->toBe(18)
        ->and(Product::whereNull('category_id')->count())->toBe(0);
});

test('seeds products with both tax rates so the difference is visible', function () {
    expect(Product::where('tax_rate', 10)->count())->toBe(15)
        ->and(Product::where('tax_rate', 8)->count())->toBe(3);
});

test('seeds stock states that show every badge', function () {
    expect(Product::where('stock', 0)->exists())->toBeTrue()             // うりきれ
        ->and(Product::whereBetween('stock', [1, 3])->exists())->toBeTrue() // のこりわずか
        ->and(Product::where('stock', '>', 3)->exists())->toBeTrue();       // ざいこあり
});

test('seeds the users with an allowance', function () {
    expect(User::count())->toBe(3);

    $user = User::where('email', 'test@example.com')->firstOrFail();

    expect($user->allowance_balance)->toBeGreaterThan(0);
});

test('seeds orders whose totals match their lines', function () {
    expect(Order::count())->toBe(2);

    foreach (Order::with('details')->get() as $order) {
        expect($order->subtotal)->toBe((int) $order->details->sum('subtotal'))
            ->and($order->tax_total)->toBe((int) $order->details->sum('tax_amount'))
            ->and($order->total_price)->toBe($order->subtotal + $order->tax_total)
            ->and($order->change_amount)->toBe($order->paid_amount - $order->total_price)
            ->and($order->change_amount)->toBeGreaterThanOrEqual(0);
    }
});

test('seeds the supporting shop data', function () {
    expect(News::published()->count())->toBe(5)
        ->and(Review::count())->toBeGreaterThan(0)
        ->and(Favorite::count())->toBeGreaterThan(0)
        ->and(StockMovement::count())->toBeGreaterThan(0)
        ->and(AllowanceTransaction::count())->toBeGreaterThan(0);
});

test('seeded reviews are only 1 to 5 stars', function () {
    expect(Review::whereNotBetween('rating', [1, 5])->count())->toBe(0);
});

test('the seeded shop pages all render', function () {
    /** @var TestCase $this */
    $this->get(route('home'))->assertSuccessful();
    $this->get(route('news.index'))->assertSuccessful();
    $this->get(route('cart.index'))->assertSuccessful();

    $product = Product::firstOrFail();
    $this->get(route('products.show', $product))->assertSuccessful();

    $user = User::where('email', 'test@example.com')->firstOrFail();

    $this->actingAs($user)->get(route('mypage'))->assertSuccessful();
    $this->actingAs($user)->get(route('wallet.index'))->assertSuccessful();
    $this->actingAs($user)->get(route('favorites.index'))->assertSuccessful();
    $this->actingAs($user)->get(route('orders.index'))->assertSuccessful();
    $this->actingAs($user)->get(route('orders.show', $user->orders()->firstOrFail()))->assertSuccessful();
});

test('running the seeder twice does not duplicate data', function () {
    /** @var TestCase $this */
    $this->seed();

    expect(Category::count())->toBe(7)
        ->and(Product::count())->toBe(18)
        ->and(User::count())->toBe(3)
        ->and(News::count())->toBe(5);
});
