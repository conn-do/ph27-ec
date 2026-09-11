<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * じぶんの注文だけ見られる。
     */
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id;
    }
}
