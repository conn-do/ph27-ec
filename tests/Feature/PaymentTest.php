<?php

use App\Enums\PaymentStatus;
use App\Mail\OrderConfirmationMail;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use App\Services\StripeCheckoutService;
use Illuminate\Support\Facades\Mail;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Event as StripeEvent;

test('決済成功時に注文が確定し在庫が減ってカートが空になる', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 10]);
    $order = Order::factory()->for($user)->create([
        'payment_status' => PaymentStatus::Unpaid,
        'stripe_checkout_session_id' => 'cs_test_123',
    ]);
    OrderDetail::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 3,
    ]);

    $session = StripeSession::constructFrom([
        'id' => 'cs_test_123',
        'payment_status' => 'paid',
        'payment_intent' => 'pi_test_123',
    ]);

    $this->mock(StripeCheckoutService::class, function ($mock) use ($session) {
        $mock->shouldReceive('retrieveSession')->once()->andReturn($session);
    });

    CartItem::factory()->create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 3]);

    Mail::fake();

    $this->actingAs($user)
        ->get('/checkout/success?order_id='.$order->id)
        ->assertOk();

    expect($order->fresh()->payment_status)->toBe(PaymentStatus::Paid);
    expect($order->fresh()->stripe_payment_intent_id)->toBe('pi_test_123');
    expect($product->fresh()->stock)->toBe(7);
    expect(CartItem::where('user_id', $user->id)->exists())->toBeFalse();

    Mail::assertQueued(OrderConfirmationMail::class, function ($mail) use ($order, $user) {
        return $mail->order->id === $order->id
            && $mail->hasTo($user->email);
    });
});

test('決済が未完了の場合は注文を確定せずカートへ戻す', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 10]);
    $order = Order::factory()->for($user)->create([
        'payment_status' => PaymentStatus::Unpaid,
        'stripe_checkout_session_id' => 'cs_test_456',
    ]);
    OrderDetail::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $session = StripeSession::constructFrom([
        'id' => 'cs_test_456',
        'payment_status' => 'unpaid',
    ]);

    $this->mock(StripeCheckoutService::class, function ($mock) use ($session) {
        $mock->shouldReceive('retrieveSession')->once()->andReturn($session);
    });

    Mail::fake();

    $this->actingAs($user)
        ->get('/checkout/success?order_id='.$order->id)
        ->assertRedirect('/cart');

    expect($order->fresh()->payment_status)->toBe(PaymentStatus::Unpaid);
    expect($product->fresh()->stock)->toBe(10);
    Mail::assertNothingQueued();
});

test('他人の注文の決済結果は閲覧できない', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $order = Order::factory()->for($owner)->create([
        'payment_status' => PaymentStatus::Unpaid,
        'stripe_checkout_session_id' => 'cs_test_owner',
    ]);

    $this->actingAs($other)
        ->get('/checkout/success?order_id='.$order->id)
        ->assertNotFound();
});

test('決済キャンセル時は未払いの注文を破棄してカートへ戻す', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 10]);
    $order = Order::factory()->for($user)->create(['payment_status' => PaymentStatus::Unpaid]);
    OrderDetail::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $this->actingAs($user)
        ->get('/checkout/cancel?order_id='.$order->id)
        ->assertRedirect('/cart');

    expect(Order::find($order->id))->toBeNull();
});

test('Webhookで決済完了イベントを受け取ると注文が確定する', function () {
    $product = Product::factory()->create(['stock' => 10]);
    $order = Order::factory()->create([
        'payment_status' => PaymentStatus::Unpaid,
        'stripe_checkout_session_id' => 'cs_test_789',
    ]);
    OrderDetail::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 4,
    ]);

    $event = StripeEvent::constructFrom([
        'id' => 'evt_test_1',
        'type' => 'checkout.session.completed',
        'data' => [
            'object' => [
                'id' => 'cs_test_789',
                'metadata' => ['order_id' => (string) $order->id],
                'payment_intent' => 'pi_test_789',
            ],
        ],
    ]);

    $this->mock(StripeCheckoutService::class, function ($mock) use ($event) {
        $mock->shouldReceive('constructWebhookEvent')->once()->andReturn($event);
    });

    Mail::fake();

    $this->post('/stripe/webhook', [], ['Stripe-Signature' => 'test-signature'])
        ->assertOk();

    expect($order->fresh()->payment_status)->toBe(PaymentStatus::Paid);
    expect($order->fresh()->stripe_payment_intent_id)->toBe('pi_test_789');
    expect($product->fresh()->stock)->toBe(6);

    Mail::assertQueued(OrderConfirmationMail::class, function ($mail) use ($order) {
        return $mail->order->id === $order->id && $mail->hasTo($order->user->email);
    });
});

test('Webhookは同じ注文を二重に確定しない', function () {
    $product = Product::factory()->create(['stock' => 10]);
    $order = Order::factory()->create([
        'payment_status' => PaymentStatus::Paid,
        'stripe_checkout_session_id' => 'cs_test_999',
        'stripe_payment_intent_id' => 'pi_test_999',
    ]);
    OrderDetail::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $event = StripeEvent::constructFrom([
        'id' => 'evt_test_2',
        'type' => 'checkout.session.completed',
        'data' => [
            'object' => [
                'id' => 'cs_test_999',
                'metadata' => ['order_id' => (string) $order->id],
                'payment_intent' => 'pi_test_999',
            ],
        ],
    ]);

    $this->mock(StripeCheckoutService::class, function ($mock) use ($event) {
        $mock->shouldReceive('constructWebhookEvent')->once()->andReturn($event);
    });

    Mail::fake();

    $this->post('/stripe/webhook', [], ['Stripe-Signature' => 'test-signature'])
        ->assertOk();

    // すでにpaidだったので在庫はさらに減らない
    expect($product->fresh()->stock)->toBe(10);
    Mail::assertNothingQueued();
});

test('注文確認メールに注文内容が正しく描画される', function () {
    $product = Product::factory()->create(['name' => 'テスト用ノート']);
    $order = Order::factory()->create([
        'shipping_name' => '山田太郎',
        'total_price' => 1500,
    ]);
    OrderDetail::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $html = (new OrderConfirmationMail($order))->render();

    expect($html)
        ->toContain('山田太郎')
        ->toContain('テスト用ノート')
        ->toContain('1,500円');
});
