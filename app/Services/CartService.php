<?php

namespace App\Services;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * カートの中身を返す。
     *
     * @return array<int, int> 商品ID => 数量
     */
    public function items(): array
    {
        if (Auth::check()) {
            return CartItem::where('user_id', Auth::id())->pluck('quantity', 'product_id')->all();
        }

        return session()->get('cart', []);
    }

    public function isEmpty(): bool
    {
        return empty($this->items());
    }

    /**
     * 商品をカートに追加・数量を上書きする。
     */
    public function put(int $productId, int $quantity): void
    {
        if (Auth::check()) {
            CartItem::updateOrCreate(
                ['user_id' => Auth::id(), 'product_id' => $productId],
                ['quantity' => $quantity],
            );

            return;
        }

        $cart = session()->get('cart', []);
        $cart[$productId] = $quantity;
        session()->put('cart', $cart);
    }

    public function remove(int $productId): void
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->where('product_id', $productId)->delete();

            return;
        }

        $cart = session()->get('cart', []);
        unset($cart[$productId]);
        session()->put('cart', $cart);
    }

    public function clear(): void
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();

            return;
        }

        session()->forget('cart');
    }

    /**
     * ログイン前にセッションへ貯めていたカートを、ログインしたユーザーのDBカートへ統合する。
     * 同じ商品が両方にある場合は数量を合算する。
     */
    public function mergeSessionCartIntoUser(int $userId): void
    {
        $sessionCart = session()->get('cart', []);

        foreach ($sessionCart as $productId => $quantity) {
            $existing = CartItem::where('user_id', $userId)->where('product_id', $productId)->first();

            if ($existing) {
                $existing->increment('quantity', $quantity);
            } else {
                CartItem::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }
        }

        session()->forget('cart');
    }
}
