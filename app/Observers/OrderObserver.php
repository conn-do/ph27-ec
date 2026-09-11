<?php

namespace App\Observers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Mail\OrderShippedMail;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    public function updated(Order $order): void
    {
        if ($order->wasChanged('status') && $order->status === OrderStatus::Cancelled) {
            foreach ($order->details as $detail) {
                $detail->product->increment('stock', $detail->quantity);
            }

            // 決済確定時に加算したクーポンの利用回数を元に戻す
            if ($order->coupon_code && $order->payment_status === PaymentStatus::Paid) {
                Coupon::where('code', $order->coupon_code)
                    ->where('used_count', '>', 0)
                    ->decrement('used_count');
            }
        }

        if ($order->wasChanged('status') && $order->status === OrderStatus::Shipped && ! $order->shipped_at) {
            $order->update(['shipped_at' => now()]);

            Mail::to($order->user)->send(new OrderShippedMail($order));
        }
    }
}
