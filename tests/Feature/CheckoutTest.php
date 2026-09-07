<?php

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->product = Product::factory()->create(['price' => 1200, 'stock' => 5]);
    $this->token = (string) Str::uuid();
    $this->shipping = ['recipient_name' => 'テスト 太郎', 'postal_code' => '123-4567', 'address' => '東京都テスト区1-2-3', 'phone' => '090-1234-5678', 'checkout_token' => $this->token];
});

test('checkout uses server prices records snapshots and prevents repeat submissions', function () {
    $this->actingAs($this->user)->withSession(['cart' => [$this->product->id => 2], 'checkout_token' => $this->token]);
    $this->get(route('checkout'))->assertOk()->assertSee('ご注文の確認')->assertSee('name="_token"', false);
    $response = $this->post(route('orders.store'), [...$this->shipping, 'total_price' => 1, 'user_id' => 999]);
    $order = Order::sole();
    $response->assertRedirect(route('orders.show', $order))->assertSessionMissing('cart');
    expect($order->total_price)->toBe(2900)->and($order->shipping_fee)->toBe(500)->and($order->user_id)->toBe($this->user->id);
    expect($this->product->fresh()->stock)->toBe(3);
    $this->product->update(['name' => 'Renamed product', 'price' => 9999]);
    $this->get(route('orders.show', $order))->assertOk()->assertSee('¥1,200')->assertDontSee('Renamed product');
    $this->post(route('orders.store'), $this->shipping)->assertRedirect(route('orders.show', $order));
    $this->assertDatabaseCount('orders', 1);
    expect($this->product->fresh()->stock)->toBe(3);
    $this->get(route('orders.index'))->assertOk()->assertSee('¥2,900');
});
test('shipping is free at threshold and included below it', function (int $price, int $fee) {
    $this->product->update(['price' => $price]);
    $this->actingAs($this->user)->withSession(['cart' => [$this->product->id => 1], 'checkout_token' => $this->token]);
    $this->get(route('checkout'))->assertViewHas('shipping', $fee);
    $this->post(route('orders.store'), $this->shipping)->assertRedirect();
    expect(Order::sole()->shipping_fee)->toBe($fee)->and(Order::sole()->total_price)->toBe($price + $fee);
})->with([[5000, 0], [4999, 500], [0, 500]]);
test('empty checkout does not create an order', function () {
    $this->actingAs($this->user)->withSession(['cart' => [], 'checkout_token' => $this->token])
        ->post(route('orders.store'), $this->shipping)->assertRedirect(route('cart.index'))->assertSessionHasErrors('cart');
    $this->get(route('checkout'))->assertRedirect(route('cart.index'));
    $this->assertDatabaseCount('orders', 0);
});
test('stock shortage leaves the whole order unchanged', function () {
    $other = Product::factory()->create(['stock' => 0]);
    $this->actingAs($this->user)->withSession(['cart' => [$this->product->id => 2, $other->id => 1], 'checkout_token' => $this->token])
        ->post(route('orders.store'), $this->shipping)->assertSessionHasErrors('cart');
    $this->assertDatabaseCount('orders', 0);
    $this->assertDatabaseCount('order_details', 0);
    expect($this->product->fresh()->stock)->toBe(5);
});
test('a write failure rolls back stock and hides internal exception details', function () {
    OrderDetail::creating(fn () => throw new RuntimeException('sensitive database details'));
    try {
        $response = $this->actingAs($this->user)->withSession(['cart' => [$this->product->id => 1], 'checkout_token' => $this->token])
            ->post(route('orders.store'), $this->shipping);
        $response->assertRedirect(route('cart.index'))->assertSessionHasErrors('cart')->assertSessionHas('cart.'.$this->product->id, 1);
        expect(session('errors')->first('cart'))->not->toContain('sensitive');
        expect($this->product->fresh()->stock)->toBe(5);
        $this->assertDatabaseCount('orders', 0);
    } finally {
        OrderDetail::flushEventListeners();
    }
});
test('missing products cannot be checked out', function () {
    $this->actingAs($this->user)->withSession(['cart' => [999 => 1], 'checkout_token' => $this->token])
        ->post(route('orders.store'), $this->shipping)->assertSessionHasErrors('cart');
    $this->assertDatabaseCount('orders', 0);
});
test('checkout rejects invalid shipping and expired tokens', function () {
    $this->actingAs($this->user)->withSession(['cart' => [$this->product->id => 1], 'checkout_token' => $this->token]);
    $this->post(route('orders.store'), [...$this->shipping, 'postal_code' => 'invalid', 'recipient_name' => '', 'address' => '', 'phone' => 'abc'])
        ->assertRedirect(route('checkout'))->assertSessionHasErrors(['postal_code', 'recipient_name', 'address', 'phone']);
    $this->post(route('orders.store'), [...$this->shipping, 'checkout_token' => (string) Str::uuid()])->assertSessionHasErrors('cart');
    $this->assertDatabaseCount('orders', 0);
});
test('users can only read their own orders', function () {
    $order = $this->user->orders()->create(['total_price' => 500]);
    $other = User::factory()->create();
    $this->actingAs($other)->get(route('orders.show', $order))->assertNotFound();
    $this->get(route('orders.index'))->assertOk()->assertSee('まだご注文はありません。');
    $this->actingAs($this->user)->get(route('orders.show', $order))->assertOk();
});
test('guests cannot submit orders', function () {
    $this->post(route('orders.store'), $this->shipping)->assertRedirect(route('login'));
    $this->assertDatabaseCount('orders', 0);
});
