<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests cannot view order history', function () {
    $this->get(route('orders.index'))->assertRedirect(route('login'));
});

test('order history shows only the authenticated users orders in newest first order', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $olderOrder = Order::factory()->for($user)->create([
        'total_price' => 880,
        'created_at' => now()->subDay(),
    ]);
    $newerOrder = Order::factory()->for($user)->create([
        'total_price' => 1540,
        'created_at' => now(),
    ]);
    $otherOrder = Order::factory()->for($otherUser)->create();
    OrderItem::factory()->count(2)->for($olderOrder)->create();
    OrderItem::factory()->count(3)->for($newerOrder)->create();
    OrderItem::factory()->for($otherOrder)->create();

    $this->actingAs($user)
        ->get(route('orders.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('orders/index')
            ->has('orders', 2)
            ->where('orders.0.id', $newerOrder->id)
            ->where('orders.0.total_price', 1540)
            ->where('orders.0.status', 'pending')
            ->where('orders.0.items_count', 3)
            ->where('orders.1.id', $olderOrder->id)
            ->where('orders.1.items_count', 2)
            ->missing('orders.2')
        );
});
