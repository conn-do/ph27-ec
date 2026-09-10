<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function store(StoreCartItemRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $cart = $request->session()->get('cart', []);
        $cart[$validated['product_id']] = $validated['quantity'];
        $request->session()->put('cart', $cart);

        $request->session()->flash('message', 'カートに追加しました。');

        return to_route('cart.index');
    }

    public function index(Request $request): View
    {
        $cart = $request->session()->get('cart', []);
        $products = Product::whereKey(array_keys($cart))->get()->keyBy('id');
        $items = collect($cart)->map(fn (int $quantity, int|string $productId): ?array => $products->has((int) $productId)
            ? ['product' => $products->get((int) $productId), 'quantity' => $quantity]
            : null)->filter()->values();
        $totalPrice = $items->sum(fn (array $item): int => $item['product']->price * $item['quantity']);

        return view('cart', compact('items', 'totalPrice'));
    }

    public function update(UpdateCartItemRequest $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (array_key_exists($product->id, $cart)) {
            $cart[$product->id] = $request->integer('quantity');
            $request->session()->put('cart', $cart);
        }

        return to_route('cart.index')->with('message', '数量を更新しました。');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$product->id]);
        $request->session()->put('cart', $cart);

        return to_route('cart.index')->with('message', '商品をカートから削除しました。');
    }

    public function clear(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');

        return to_route('cart.index')->with('message', 'カートを空にしました。');
    }
}
