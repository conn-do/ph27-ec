<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'productId' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10',
        ], [
            'quantity.max' => '10個までしか購入できません',
        ]);
        $cart = session()->get('cart', []);
        $cart[$validated['productId']] = $validated['quantity'];
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('message', '商品をカートに追加しました');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        foreach ($cart as $productId => $quantity) {
            /** @disregard */
            $product = Product::find($productId);
            $cart[$productId] = [
                'product' => $product,
                'quantity' => $quantity,
                'total' => $product->price * $quantity,
            ];
        }

        return view('cart', [
            'cart' => $cart,
        ]);
    }

    public function clear()
    {
        session()->forget('cart');

        return redirect()->route('cart.index')->with('message', 'カートを空にしました');
    }
}
