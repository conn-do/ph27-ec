<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'productId' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10',
        ], [
            'productId.exists' => '商品が見つかりません。',
            'quantity.min' => '1個以上選択してください。',
            'quantity.max' => '10個以下を選択してください。',
        ]);

        $product = Product::findOrFail($validated['productId']);

        if ($validated['quantity'] > $product->stock) {
            return back()
                ->withErrors(['quantity' => '在庫数以内で選択してください。'])
                ->withInput();
        }

        $cart = session()->get('cart', []);
        $cart[$product->id] = $validated['quantity'];
        session()->put('cart', $cart);

        $request->session()->flash('message', 'カートに追加しました。');

        return redirect('/cart');
    }

    public function index(Request $request)
    {
        $cart = session()->get('cart', []);
        $items = [];
        $totalPrice = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);

            if (! $product) {
                unset($cart[$productId]);

                continue;
            }

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
            $totalPrice += $product->price * $quantity;
        }

        $request->session()->put('cart', $cart);

        return view('cart', [
            'items' => $items,
            'totalPrice' => $totalPrice,
        ]);
    }

    public function clear(Request $request)
    {
        session()->forget('cart');
        $request->session()->flash('message', 'カートを空にしました。');

        return redirect('/cart');
    }
}
