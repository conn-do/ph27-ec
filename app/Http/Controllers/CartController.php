<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function index(CartService $cart): Response
    {
        return Inertia::render('cart/index', [
            'cart' => $cart->summary(),
        ]);
    }

    public function store(AddToCartRequest $request, Product $product, CartService $cart): RedirectResponse
    {
        $error = $cart->add($product, $request->integer('quantity', 1));

        if ($error !== null) {
            return back()->with('error', $error);
        }

        return back();
    }

    public function update(UpdateCartRequest $request, Product $product, CartService $cart): RedirectResponse
    {
        $error = $cart->update($product, $request->integer('quantity'));

        if ($error !== null) {
            return to_route('cart.index')->with('error', $error);
        }

        return to_route('cart.index')->with('success', '数量を更新しました。');
    }

    public function destroy(Product $product, CartService $cart): RedirectResponse
    {
        $cart->remove($product);

        return to_route('cart.index')->with('success', '商品をカートから削除しました。');
    }
}
