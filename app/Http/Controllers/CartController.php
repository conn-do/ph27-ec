<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ], [
            'quantity.min' => '1個以上選択してください。',
            'quantity.max' => '10個以下を選択してください。',
        ]);

        $cart = session()->get('cart', []);
        $cart[$validated['product_id']] = $validated['quantity'];
        session()->put('cart', $cart);

        return to_route('cart.index')->with('message', 'カートに追加しました。');
    }

    public function index(): View
    {
        $cart = session()->get('cart', []);
        $products = Product::query()->whereKey(array_keys($cart))->get()->keyBy('id');

        $items = collect($cart)
            ->map(function ($quantity, $productId) use ($products): ?array {
                $product = $products->get($productId);

                if (! $product instanceof Product) {
                    return null;
                }

                return [
                    'product' => $product,
                    'quantity' => (int) $quantity,
                ];
            })
            ->filter()
            ->values();

        return view('cart', [
            'items' => $items,
            'totalPrice' => $items->sum(fn (array $item): int => $item['product']->price * $item['quantity']),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $cart = session()->get('cart', []);

        if (array_key_exists($product->id, $cart)) {
            $cart[$product->id] = $validated['quantity'];
            session()->put('cart', $cart);
        }

        return to_route('cart.index')->with('message', 'カートの数量を更新しました。');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $cart = session()->get('cart', []);
        unset($cart[$product->id]);
        session()->put('cart', $cart);

        return to_route('cart.index')->with('message', '商品をカートから削除しました。');
    }

    public function clear(): RedirectResponse
    {
        session()->forget('cart');

        return to_route('cart.index')->with('message', 'カートを空にしました。');
    }
}
