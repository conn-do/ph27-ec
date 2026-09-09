<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CartController extends Controller
{
    public function store(StoreCartItemRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $product = Product::findOrFail($validated['productId']);
        $cart = $request->session()->get('cart', []);
        $quantity = ($cart[$product->id] ?? 0) + $validated['quantity'];

        $this->ensureQuantityIsAvailable($product, $quantity);

        $cart[$product->id] = $quantity;
        $request->session()->put('cart', $cart);

        $request->session()->flash('message', 'カートに追加しました。');

        return to_route('cart.index');
    }

    public function index(Request $request): View
    {
        $cart = $request->session()->get('cart', []);
        $products = Product::query()->whereKey(array_keys($cart))->get()->keyBy('id');
        $items = collect($cart)
            ->filter(fn (int $quantity, int|string $productId): bool => $products->has($productId))
            ->map(fn (int $quantity, int|string $productId): array => [
                'subtotal' => $products->get($productId)->price * $quantity,
                'product' => $products->get($productId),
                'quantity' => $quantity,
            ])
            ->values();

        return view('cart', [
            'items' => $items,
            'totalPrice' => $items->sum('subtotal'),
        ]);
    }

    public function update(UpdateCartItemRequest $request, Product $product): RedirectResponse
    {
        $quantity = $request->integer('quantity');
        $this->ensureQuantityIsAvailable($product, $quantity);

        $request->session()->put("cart.{$product->id}", $quantity);
        $request->session()->flash('message', '数量を更新しました。');

        return to_route('cart.index');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $request->session()->forget("cart.{$product->id}");
        $request->session()->flash('message', '商品をカートから削除しました。');

        return to_route('cart.index');
    }

    public function clear(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');
        $request->session()->flash('message', 'カートを空にしました。');

        return to_route('cart.index');
    }

    private function ensureQuantityIsAvailable(Product $product, int $quantity): void
    {
        if ($quantity > $product->stock) {
            throw ValidationException::withMessages([
                'quantity' => '在庫数を超える数量は選択できません。',
            ]);
        }
    }
}
