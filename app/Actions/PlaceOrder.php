<?php

namespace App\Actions;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PlaceOrder
{
    /** @param array<int, int> $cart
     * @param  array{recipient_name: string, postal_code: string, address: string, phone: string, checkout_token: string}  $shipping
     */
    public function handle(User $user, array $cart, array $shipping): Order
    {
        if ($cart === []) {
            throw ValidationException::withMessages(['cart' => 'カートが空です。商品を追加してください。']);
        }

        return DB::transaction(function () use ($user, $cart, $shipping): Order {
            $products = Product::whereIn('id', array_keys($cart))->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $subtotal = 0;
            foreach ($cart as $id => $quantity) {
                $product = $products->get($id);
                if (! $product || ! is_int($quantity) || $quantity < 1 || $quantity > config('shop.max_quantity') || $quantity > $product->stock) {
                    throw ValidationException::withMessages(['cart' => '商品の在庫が不足しているか、販売が終了しました。カートをご確認ください。']);
                }
                $subtotal += $product->price * $quantity;
            }
            $fee = $subtotal >= config('shop.free_shipping_threshold') ? 0 : config('shop.shipping_fee');
            $order = new Order;
            $order->forceFill($shipping);
            $order->user_id = $user->id;
            $order->shipping_fee = $fee;
            $order->total_price = $subtotal + $fee;
            $order->save();
            foreach ($cart as $id => $quantity) {
                $product = $products->get($id);
                $changed = Product::whereKey($id)->where('stock', '>=', $quantity)->decrement('stock', $quantity);
                if ($changed !== 1) {
                    throw ValidationException::withMessages(['cart' => '在庫が変更されました。カートをご確認ください。']);
                }
                $order->details()->create([
                    'product_id' => $id,
                    'product_name' => $product->name,
                    'unit_price' => $product->price,
                    'quantity' => $quantity,
                ]);
            }

            return $order;
        }, attempts: 3);
    }
}
