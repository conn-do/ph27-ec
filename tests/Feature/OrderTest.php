<?php

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\StripeCheckoutService;

test('カートに商品があれば配送先入力画面を表示できる', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 5]);
    $this->actingAs($user)->withSession(['cart' => [$product->id => 2]]);

    $this->get('/orders/create')->assertOk();
});

test('カートが空だと配送先入力画面に進めずカートへ戻される', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/orders/create')
        ->assertRedirect('/cart');
});

test('配送先を入力すると未払いの注文が作成され決済画面へリダイレクトされる', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 5, 'price' => 1000]);
    $this->actingAs($user)->withSession(['cart' => [$product->id => 2]]);

    $this->mock(StripeCheckoutService::class, function ($mock) {
        $mock->shouldReceive('createSession')
            ->once()
            ->andReturn('https://checkout.stripe.com/test-session');
    });

    $response = $this->post('/orders', [
        'shipping_name' => '山田太郎',
        'shipping_postal_code' => '123-4567',
        'shipping_address' => '東京都渋谷区1-1-1',
        'shipping_phone' => '090-1234-5678',
    ]);

    $response->assertRedirect('https://checkout.stripe.com/test-session');

    $order = Order::first();
    expect($order->shipping_name)->toBe('山田太郎');
    expect($order->shipping_postal_code)->toBe('123-4567');
    expect($order->shipping_address)->toBe('東京都渋谷区1-1-1');
    expect($order->shipping_phone)->toBe('090-1234-5678');
    expect($order->total_price)->toBe(2000);
    expect($order->payment_status)->toBe(PaymentStatus::Unpaid);
    // 決済完了前なのでカートはまだ残っており、在庫も減っていない
    expect(session('cart'))->not->toBeNull();
    expect($product->fresh()->stock)->toBe(5);
});

test('在庫を超える注文はできない', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 1, 'price' => 1000]);
    $this->actingAs($user)->withSession(['cart' => [$product->id => 2]]);

    $response = $this->post('/orders', [
        'shipping_name' => '山田太郎',
        'shipping_postal_code' => '123-4567',
        'shipping_address' => '東京都渋谷区1-1-1',
        'shipping_phone' => '090-1234-5678',
    ]);

    $response->assertRedirect('/cart');
    expect(Order::count())->toBe(0);
});

test('配送先を入力しないと注文できない', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 5]);
    $this->actingAs($user)->withSession(['cart' => [$product->id => 1]]);

    $response = $this->post('/orders', []);

    $response->assertSessionHasErrors([
        'shipping_name',
        'shipping_postal_code',
        'shipping_address',
        'shipping_phone',
    ]);
    expect(Order::count())->toBe(0);
});

test('カートが空のままPOSTしても注文できない', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/orders', [
        'shipping_name' => '山田太郎',
        'shipping_postal_code' => '123-4567',
        'shipping_address' => '東京都渋谷区1-1-1',
        'shipping_phone' => '090-1234-5678',
    ]);

    $response->assertRedirect('/cart');
    expect(Order::count())->toBe(0);
});
