<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;

test('注文キャンセル時に在庫が戻る', function () {
    $product = Product::factory()->create(['stock' => 10]);
    $order = Order::factory()->create(['status' => OrderStatus::Pending]);

    OrderDetail::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 3,
    ]);

    $order->update(['status' => OrderStatus::Cancelled]);

    expect($product->fresh()->stock)->toBe(13);
});

test('注文の持ち主は自分の注文をキャンセルできる', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 10]);
    $order = Order::factory()->for($user)->create(['status' => OrderStatus::Pending]);
    OrderDetail::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 3,
    ]);

    $this->actingAs($user)
        ->post("/orders/{$order->id}/cancel")
        ->assertRedirect("/orders/{$order->id}");

    expect($order->fresh()->status)->toBe(OrderStatus::Cancelled);
    expect($product->fresh()->stock)->toBe(13);
});

test('他人の注文はキャンセルできない', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $order = Order::factory()->for($owner)->create(['status' => OrderStatus::Pending]);

    $this->actingAs($other)
        ->post("/orders/{$order->id}/cancel")
        ->assertForbidden();

    expect($order->fresh()->status)->toBe(OrderStatus::Pending);
});

test('発送済みの注文はキャンセルできない', function () {
    $user = User::factory()->create();
    $order = Order::factory()->for($user)->create(['status' => OrderStatus::Shipped]);

    $this->actingAs($user)->post("/orders/{$order->id}/cancel");

    expect($order->fresh()->status)->toBe(OrderStatus::Shipped);
});
