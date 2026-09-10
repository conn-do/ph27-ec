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

        $cart = session()->get('cart', []);
        $currentQuantity = (int) ($cart[$validated['productId']] ?? 0);
        $cart[$validated['productId']] = min($currentQuantity + $validated['quantity'], 10);
        session()->put('cart', $cart);

        $request->session()->flash('message', 'カートに追加しました。');

        return redirect('/cart');
    }

    public function index(): View
    {
        $cart = session()->get('cart', []);
        $products = Product::query()
            ->whereKey(array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = collect($cart)
            ->map(function (int $quantity, int|string $productId) use ($products): ?array {
                $product = $products->get((int) $productId);

                return $product === null ? null : [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity,
                ];
            })
            ->filter()
            ->values();

        return view('cart', [
            'items' => $items,
            'totalPrice' => $items->sum('subtotal'),
        ]);
    }

    public function update(UpdateCartItemRequest $request, Product $product): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (! array_key_exists($product->id, $cart)) {
            return redirect()->route('cart.index')->withErrors([
                'cart' => '指定された商品はカートにありません。',
            ]);
        }

        $cart[$product->id] = $request->integer('quantity');
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('message', '数量を更新しました。');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $cart = session()->get('cart', []);
        unset($cart[$product->id]);
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('message', '商品をカートから削除しました。');
    }

    public function clear(Request $request): RedirectResponse
    {
        session()->forget('cart');
        $request->session()->flash('message', 'カートを空にしました。');

        return redirect()->route('cart.index');
    }
}
