<?php

namespace App\Observers;

use App\Enums\OrderStatus;
use App\Models\Order;

class OrderObserver
{
    public function updated(Order $order): void
    {
        if ($order->wasChanged('status') && $order->status === OrderStatus::Cancelled) {
            foreach ($order->details as $detail) {
                $detail->product->increment('stock', $detail->quantity);
            }
        }
    }
}
