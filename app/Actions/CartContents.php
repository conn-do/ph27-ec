<?php

namespace App\Actions;

use App\Models\Product;
use Illuminate\Http\Request;

class CartContents
{
    /** @return array{items: array, subtotal: int, shipping: int, totalPrice: int} */
    public function handle(Request $request): array
    {
        $cart = $request->session()->get('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $items = [];
        $subtotal = 0;
        foreach ($cart as $id => $quantity) {
            $product = $products->get($id);
            if (! $product) {
                unset($cart[$id]);
                $request->session()->flash('message', '販売を終了した商品をカートから削除しました。');

                continue;
            }
            $items[] = ['product' => $product, 'quantity' => $quantity];
            $subtotal += $product->price * $quantity;
        }
        $request->session()->put('cart', $cart);
        $shipping = $items === [] || $subtotal >= config('shop.free_shipping_threshold') ? 0 : config('shop.shipping_fee');

        return ['items' => $items, 'subtotal' => $subtotal, 'shipping' => $shipping, 'totalPrice' => $subtotal + $shipping];
    }
}
