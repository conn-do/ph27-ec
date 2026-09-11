<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use App\Services\StripeCheckoutService;
use Stripe\Checkout\Session as StripeSession;

test('有効なクーポンコードを適用するとカートの合計が割引される', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 5, 'price' => 1000]);
    CartItem::factory()->create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 2]);
    Coupon::factory()->create(['code' => 'SAVE500', 'value' => 500]);

    $this->actingAs($user)
        ->post('/cart/coupon', ['code' => 'save500'])
        ->assertRedirect('/cart');

    $response = $this->actingAs($user)->get('/cart');

    $response->assertOk();
    $response->assertSee('SAVE500');
    $response->assertSee('1,500円');
});

test('存在しないクーポンコードは適用されない', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/cart/coupon', ['code' => 'NOTEXIST']);

    $response->assertRedirect('/cart');
    expect(session('coupon_code'))->toBeNull();
});

test('期限切れのクーポンは適用されない', function () {
    $user = User::factory()->create();
    Coupon::factory()->expired()->create(['code' => 'EXPIRED']);

    $this->actingAs($user)->post('/cart/coupon', ['code' => 'EXPIRED']);

    expect(session('coupon_code'))->toBeNull();
});

test('利用上限に達したクーポンは適用されない', function () {
    $user = User::factory()->create();
    Coupon::factory()->usageLimitReached()->create(['code' => 'MAXED']);

    $this->actingAs($user)->post('/cart/coupon', ['code' => 'MAXED']);

    expect(session('coupon_code'))->toBeNull();
});

test('クーポンを解除できる', function () {
    $user = User::factory()->create();
    Coupon::factory()->create(['code' => 'SAVE500', 'value' => 500]);
    session()->put('coupon_code', 'SAVE500');

    $this->actingAs($user)->get('/cart/coupon/remove')->assertRedirect('/cart');

    expect(session('coupon_code'))->toBeNull();
});

test('注文確定時にクーポンの割引が注文へ反映される', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 5, 'price' => 1000]);
    CartItem::factory()->create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 2]);
    Coupon::factory()->create(['code' => 'SAVE500', 'value' => 500]);
    session()->put('coupon_code', 'SAVE500');

    $this->mock(StripeCheckoutService::class, function ($mock) {
        $mock->shouldReceive('createSession')->once()->andReturn('https://checkout.stripe.com/test-session');
    });

    $this->actingAs($user)->post('/orders', [
        'shipping_name' => '山田太郎',
        'shipping_postal_code' => '123-4567',
        'shipping_address' => '東京都渋谷区1-1-1',
        'shipping_phone' => '090-1234-5678',
    ])->assertRedirect('https://checkout.stripe.com/test-session');

    $order = Order::first();
    expect($order->coupon_code)->toBe('SAVE500');
    expect($order->discount_amount)->toBe(500);
    expect($order->total_price)->toBe(1500);
});

test('割引額が小計を超える場合は0円未満にならない', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 5, 'price' => 300]);
    CartItem::factory()->create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 1]);
    Coupon::factory()->create(['code' => 'SAVE500', 'value' => 500]);
    session()->put('coupon_code', 'SAVE500');

    $this->mock(StripeCheckoutService::class, function ($mock) {
        $mock->shouldReceive('createSession')->once()->andReturn('https://checkout.stripe.com/test-session');
    });

    $this->actingAs($user)->post('/orders', [
        'shipping_name' => '山田太郎',
        'shipping_postal_code' => '123-4567',
        'shipping_address' => '東京都渋谷区1-1-1',
        'shipping_phone' => '090-1234-5678',
    ]);

    $order = Order::first();
    expect($order->discount_amount)->toBe(300);
    expect($order->total_price)->toBe(0);
});

test('決済確定時にクーポンの利用回数が加算される', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 10]);
    $coupon = Coupon::factory()->create(['code' => 'SAVE500', 'value' => 500, 'used_count' => 0]);
    $order = Order::factory()->for($user)->create([
        'payment_status' => PaymentStatus::Unpaid,
        'stripe_checkout_session_id' => 'cs_test_coupon',
        'coupon_code' => 'SAVE500',
        'discount_amount' => 500,
    ]);
    OrderDetail::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $session = StripeSession::constructFrom([
        'id' => 'cs_test_coupon',
        'payment_status' => 'paid',
        'payment_intent' => 'pi_test_coupon',
    ]);

    $this->mock(StripeCheckoutService::class, function ($mock) use ($session) {
        $mock->shouldReceive('retrieveSession')->once()->andReturn($session);
    });

    $this->actingAs($user)->get('/checkout/success?order_id='.$order->id)->assertOk();

    expect($coupon->fresh()->used_count)->toBe(1);
});

test('支払い済み注文をキャンセルするとクーポンの利用回数が戻る', function () {
    $user = User::factory()->create();
    $coupon = Coupon::factory()->create(['code' => 'SAVE500', 'value' => 500, 'used_count' => 1]);
    $order = Order::factory()->for($user)->create([
        'status' => OrderStatus::Pending,
        'payment_status' => PaymentStatus::Paid,
        'coupon_code' => 'SAVE500',
        'discount_amount' => 500,
    ]);

    $this->actingAs($user)->post("/orders/{$order->id}/cancel")->assertRedirect("/orders/{$order->id}");

    expect($coupon->fresh()->used_count)->toBe(0);
});
