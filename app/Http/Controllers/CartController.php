<?php

namespace App\Http\Controllers;

use App\Actions\CartContents;
use App\Http\Requests\CartItemRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request, CartContents $contents): View
    {
        return view('cart', $contents->handle($request));
    }

    public function store(CartItemRequest $request): RedirectResponse
    {
        $product = Product::findOrFail($request->integer('productId'));
        $cart = $request->session()->get('cart', []);
        $quantity = ($cart[$product->id] ?? 0) + $request->integer('quantity');
        $this->validateStock($product, $quantity);
        $cart[$product->id] = $quantity;
        $request->session()->put('cart', $cart);

        return to_route('cart.index')->with('message', 'カートに追加しました。');
    }

    public function update(CartItemRequest $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        abort_unless(array_key_exists($product->id, $cart), 404);
        $this->validateStock($product, $request->integer('quantity'));
        $cart[$product->id] = $request->integer('quantity');
        $request->session()->put('cart', $cart);

        return to_route('cart.index')->with('message', '数量を更新しました。');
    }

    public function destroy(Request $request, string $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$product]);
        $request->session()->put('cart', $cart);

        return to_route('cart.index')->with('message', '商品をカートから削除しました。');
    }

    public function clear(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');

        return to_route('cart.index')->with('message', 'カートを空にしました。');
    }

    private function validateStock(Product $product, int $quantity): void
    {
        if ($quantity > min($product->stock, config('shop.max_quantity'))) {
            throw ValidationException::withMessages(['quantity' => '在庫数または購入上限を超えています。カート内の数量をご確認ください。']);
        }
    }
}
