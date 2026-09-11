<?php

use App\Models\AllowanceTransaction;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

test('guests cannot see the order history', function () {
    /** @var TestCase $this */
    $this->get(route('orders.index'))->assertRedirect(route('login'));
    $this->get(route('mypage'))->assertRedirect(route('login'));
    $this->get(route('wallet.index'))->assertRedirect(route('login'));
});

test('the history lists only my own orders', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();

    $mine = Order::factory()->for($user)->create(['order_number' => 'R-MINE1234']);
    Order::factory()->for(User::factory())->create(['order_number' => 'R-THEIRS12']);

    OrderDetail::factory()->for($mine)->create();

    $this->actingAs($user)
        ->get(route('orders.index'))
        ->assertSuccessful()
        ->assertSee('R-MINE1234')
        ->assertDontSee('R-THEIRS12');
});

test('cannot open someone else order', function () {
    /** @var TestCase $this */
    $order = Order::factory()->for(User::factory())->create();

    $this->actingAs(User::factory()->create())
        ->get(route('orders.show', $order))
        ->assertForbidden();
});

test('cannot open someone else receipt', function () {
    /** @var TestCase $this */
    $order = Order::factory()->for(User::factory())->create();

    $this->actingAs(User::factory()->create())
        ->get(route('orders.complete', $order))
        ->assertForbidden();
});

test('the order detail page shows how the total was worked out', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $product = Product::factory()->create(['name' => 'ほしぞらノート', 'price' => 200, 'tax_rate' => 10]);

    $order = Order::factory()->for($user)->create([
        'subtotal' => 400,
        'tax_total' => 40,
        'total_price' => 440,
        'paid_amount' => 500,
        'change_amount' => 60,
    ]);

    OrderDetail::factory()->for($order)->forProduct($product, 2)->create();

    $this->actingAs($user)
        ->get(route('orders.show', $order))
        ->assertSuccessful()
        ->assertSee('ほしぞらノート')
        ->assertSee('200えん × 2こ')
        ->assertSee('だした おかね')
        ->assertSee('おつり');
});

test('my page shows the allowance and the counts', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $user->forceFill(['allowance_balance' => 1234])->save();

    Order::factory()->for($user)->count(2)->create();

    $this->actingAs($user)
        ->get(route('mypage'))
        ->assertSuccessful()
        ->assertSee('1,234')
        ->assertSee('いまの おこづかい');
});

test('the allowance book lists the money that was spent', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();

    AllowanceTransaction::create([
        'user_id' => $user->id,
        'amount' => -440,
        'balance_after' => 560,
        'reason' => AllowanceTransaction::REASON_SHOPPING,
    ]);

    $this->actingAs($user)
        ->get(route('wallet.index'))
        ->assertSuccessful()
        ->assertSee('-440えん')
        ->assertSee('のこり 560えん');
});
