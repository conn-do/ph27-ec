<?php

use App\Models\AllowanceTransaction;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Tests\TestCase;

test('guests are sent to the login page', function () {
    /** @var TestCase $this */
    $this->get(route('checkout.show'))->assertRedirect(route('login'));
});

test('an empty cart goes back to the cart page', function () {
    /** @var TestCase $this */
    $this->actingAs(User::factory()->create())
        ->get(route('checkout.show'))
        ->assertRedirect(route('cart.index'));
});

test('shows the calculation and the money buttons', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create(['price' => 200, 'stock' => 10, 'tax_rate' => 10]);

    $this->actingAs(User::factory()->create())
        ->withSession(['cart' => [$product->id => 1]])
        ->get(route('checkout.show'))
        ->assertSuccessful()
        ->assertSee('おかねを だそう')
        ->assertSee('100えん')
        ->assertSee('220'); // 200 + ぜい 20
});

test('buying stores the order with its calculation broken down', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $user->forceFill(['allowance_balance' => 1000])->save();

    $product = Product::factory()->create(['price' => 200, 'stock' => 10, 'tax_rate' => 10]);

    $this->actingAs($user)
        ->withSession(['cart' => [$product->id => 2]])
        ->post(route('checkout.store'), ['paid_amount' => 500]);

    $order = Order::firstOrFail();

    // 200 × 2 = 400、ぜい 40、ごうけい 440、おつり 500 − 440 = 60
    expect($order->subtotal)->toBe(400)
        ->and($order->tax_total)->toBe(40)
        ->and($order->total_price)->toBe(440)
        ->and($order->paid_amount)->toBe(500)
        ->and($order->change_amount)->toBe(60)
        ->and($order->order_number)->toStartWith('R-');
});

test('buying stores a snapshot of the product on the order line', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $product = Product::factory()->create(['name' => 'ラムネ', 'price' => 30, 'stock' => 10, 'tax_rate' => 8]);

    $this->actingAs($user)
        ->withSession(['cart' => [$product->id => 3]])
        ->post(route('checkout.store'), ['paid_amount' => 1000]);

    $detail = Order::firstOrFail()->details()->firstOrFail();

    // 30 × 3 = 90、90 × 8 ÷ 100 = 7.2 → きりすてて 7
    expect($detail->product_name)->toBe('ラムネ')
        ->and($detail->unit_price)->toBe(30)
        ->and($detail->tax_rate)->toBe(8)
        ->and($detail->quantity)->toBe(3)
        ->and($detail->subtotal)->toBe(90)
        ->and($detail->tax_amount)->toBe(7);
});

test('buying decreases the stock and records the movement', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 100, 'stock' => 10]);

    $this->actingAs($user)
        ->withSession(['cart' => [$product->id => 3]])
        ->post(route('checkout.store'), ['paid_amount' => 1000]);

    expect($product->fresh()->stock)->toBe(7);

    $movement = StockMovement::firstOrFail();

    expect($movement->quantity_change)->toBe(-3)
        ->and($movement->stock_after)->toBe(7)
        ->and($movement->reason)->toBe(StockMovement::REASON_SOLD);
});

test('buying takes the money out of the allowance', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $user->forceFill(['allowance_balance' => 1000])->save();

    $product = Product::factory()->create(['price' => 200, 'stock' => 10, 'tax_rate' => 10]);

    $this->actingAs($user)
        ->withSession(['cart' => [$product->id => 1]])
        ->post(route('checkout.store'), ['paid_amount' => 500]);

    // 220 えん つかったので のこりは 780
    expect($user->fresh()->allowance_balance)->toBe(780);

    $transaction = AllowanceTransaction::firstOrFail();

    expect($transaction->amount)->toBe(-220)
        ->and($transaction->balance_after)->toBe(780)
        ->and($transaction->reason)->toBe(AllowanceTransaction::REASON_SHOPPING);
});

test('the cart is emptied after buying', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create(['price' => 100, 'stock' => 10]);

    $this->actingAs(User::factory()->create())
        ->withSession(['cart' => [$product->id => 1]])
        ->post(route('checkout.store'), ['paid_amount' => 1000]);

    expect(session('cart'))->toBeNull();
});

test('cannot buy when the money handed over is not enough', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create(['price' => 200, 'stock' => 10, 'tax_rate' => 10]);

    $this->actingAs(User::factory()->create())
        ->withSession(['cart' => [$product->id => 1]])
        ->post(route('checkout.store'), ['paid_amount' => 100])
        ->assertSessionHasErrors('paid_amount');

    expect(Order::count())->toBe(0)
        ->and($product->fresh()->stock)->toBe(10);
});

test('cannot hand over more money than the allowance holds', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $user->forceFill(['allowance_balance' => 300])->save();

    $product = Product::factory()->create(['price' => 100, 'stock' => 10]);

    $this->actingAs($user)
        ->withSession(['cart' => [$product->id => 1]])
        ->post(route('checkout.store'), ['paid_amount' => 1000])
        ->assertSessionHasErrors('paid_amount');

    expect(Order::count())->toBe(0);
});

test('cannot buy more than the stock', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create(['price' => 100, 'stock' => 5]);

    // カートに入れたあとで ざいこが へった じょうきょう
    $this->actingAs(User::factory()->create())
        ->withSession(['cart' => [$product->id => 5]]);

    $product->update(['stock' => 2]);

    $this->actingAs(User::factory()->create())
        ->withSession(['cart' => [$product->id => 5]])
        ->post(route('checkout.store'), ['paid_amount' => 1000])
        ->assertSessionHasErrors('cart');

    expect(Order::count())->toBe(0)
        ->and($product->fresh()->stock)->toBe(2);
});

test('nothing is saved when the order fails halfway', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $ok = Product::factory()->create(['price' => 100, 'stock' => 10]);
    $short = Product::factory()->create(['price' => 100, 'stock' => 10]);

    $this->actingAs($user)->withSession(['cart' => [$ok->id => 1, $short->id => 5]]);

    $short->update(['stock' => 1]);

    $this->actingAs($user)
        ->withSession(['cart' => [$ok->id => 1, $short->id => 5]])
        ->post(route('checkout.store'), ['paid_amount' => 1000]);

    // トランザクションで もどるので 注文も ざいこの へりも のこらない
    expect(Order::count())->toBe(0)
        ->and($ok->fresh()->stock)->toBe(10)
        ->and(StockMovement::count())->toBe(0);
});

test('the receipt shows the change broken into coins', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 200, 'stock' => 10, 'tax_rate' => 10]);

    $this->actingAs($user)
        ->withSession(['cart' => [$product->id => 1]])
        ->post(route('checkout.store'), ['paid_amount' => 1000]);

    // おつり 780 = 500×1 + 100×2 + 50×1 + 10×3
    $this->get(route('orders.complete', Order::firstOrFail()))
        ->assertSuccessful()
        ->assertSee('おつりの けいさん')
        ->assertSee('500えん × 1まい')
        ->assertSee('100えん × 2まい')
        ->assertSee('10えん × 3まい');
});
