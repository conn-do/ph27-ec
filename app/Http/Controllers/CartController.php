<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CartController extends Controller
{
    public function store(CartRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $product = Product::findOrFail($validated['productId']);
        if ($validated['quantity'] > $product->stock) {
            throw ValidationException::withMessages(['quantity' => '在庫数を超えています。数量を変更してください。']);
        }
        $cart = $request->session()->get('cart', []);
        $cart[$product->id] = (int) $validated['quantity'];
        $request->session()->put('cart', $cart);

        return to_route('cart.index')->with('message', 'カートを更新しました。');
    }

    public function index(Request $request): View
    {
        $cart = $request->session()->get('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get();
        $items = [];
        $totalPrice = 0;
        $availableCart = [];
        foreach ($products as $product) {
            $quantity = $cart[$product->id];
            $items[] = ['product' => $product, 'quantity' => $quantity];
            $availableCart[$product->id] = $quantity;
            $totalPrice += $product->price * $quantity;
        }
        $request->session()->put('cart', $availableCart);

        return view('cart', compact('items', 'totalPrice'));
    }

    public function destroy(Request $request, string $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$product]);
        $request->session()->put('cart', $cart);

        return to_route('cart.index')->with('message', '商品を削除しました。');
    }

    public function clear(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');

        return to_route('cart.index')->with('message', 'カートを空にしました。');
    }
}
