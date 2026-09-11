<?php

use App\Enums\OrderStatus;
use App\Mail\OrderShippedMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('注文が発送済みになると発送日時が自動で記録される', function () {
    $order = Order::factory()->create(['status' => OrderStatus::Pending]);

    expect($order->shipped_at)->toBeNull();

    $order->update(['status' => OrderStatus::Shipped]);

    expect($order->fresh()->shipped_at)->not->toBeNull();
});

test('発送日時は最初に発送済みになった時のみ設定される', function () {
    $order = Order::factory()->create(['status' => OrderStatus::Pending]);

    $order->update(['status' => OrderStatus::Shipped]);
    $firstShippedAt = $order->fresh()->shipped_at;

    $order->update(['carrier' => 'ヤマト運輸']);

    expect($order->fresh()->shipped_at->equalTo($firstShippedAt))->toBeTrue();
});

test('発送済みの注文詳細ページに配送業者と追跡番号が表示される', function () {
    $user = User::factory()->create();
    $order = Order::factory()->for($user)->create([
        'status' => OrderStatus::Shipped,
        'carrier' => 'ヤマト運輸',
        'tracking_number' => '1234-5678-9012',
    ]);

    $response = $this->actingAs($user)->get("/orders/{$order->id}");

    $response->assertOk();
    $response->assertSee('ヤマト運輸');
    $response->assertSee('1234-5678-9012');
});

test('処理中の注文詳細ページには配送状況が表示されない', function () {
    $user = User::factory()->create();
    $order = Order::factory()->for($user)->create(['status' => OrderStatus::Pending]);

    $response = $this->actingAs($user)->get("/orders/{$order->id}");

    $response->assertOk();
    $response->assertDontSee('配送状況');
});

test('注文が発送済みになると発送通知メールが送信される', function () {
    Mail::fake();

    $user = User::factory()->create();
    $order = Order::factory()->for($user)->create(['status' => OrderStatus::Pending]);

    $order->update([
        'status' => OrderStatus::Shipped,
        'carrier' => 'ヤマト運輸',
        'tracking_number' => '1234-5678-9012',
    ]);

    Mail::assertQueued(OrderShippedMail::class, function ($mail) use ($order, $user) {
        return $mail->order->id === $order->id && $mail->hasTo($user->email);
    });
});

test('発送済みへの2回目以降の更新では発送通知メールが再送されない', function () {
    Mail::fake();

    $order = Order::factory()->create(['status' => OrderStatus::Pending]);
    $order->update(['status' => OrderStatus::Shipped]);
    $order->update(['carrier' => 'ヤマト運輸']);

    Mail::assertQueued(OrderShippedMail::class, 1);
});

test('発送通知メールに追跡情報が正しく描画される', function () {
    $order = Order::factory()->create([
        'shipping_name' => '山田太郎',
        'carrier' => 'ヤマト運輸',
        'tracking_number' => '1234-5678-9012',
        'shipped_at' => now(),
    ]);

    $html = (new OrderShippedMail($order))->render();

    expect($html)
        ->toContain('山田太郎')
        ->toContain('ヤマト運輸')
        ->toContain('1234-5678-9012');
});
