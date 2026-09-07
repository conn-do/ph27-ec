<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;

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