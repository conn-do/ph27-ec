<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\CartService;
use Illuminate\Auth\Events\Login;

class MergeGuestCartOnLogin
{
    public function __construct(private CartService $cart) {}

    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;

        $this->cart->mergeSessionCartIntoUser($user->id);
    }
}
