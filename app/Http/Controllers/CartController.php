<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ], [
            'quantity.required' => '数量を入力してください',
            'quantity.integer' => '数量は整数で入力してください',
            'quantity.min' => '数量は1以上で入力してください',
        ]);
        
        $productId = $request->input('productId');
        $quantity = $request->input('quantity');
        $product = Product::find($productId);
        if (!$product) {
            return redirect()->back()->with('error', '商品が見つかりません');
        }
        
        $cart = $request->session()->get('cart', []);

        $cart[$productId] = $quantity;
        session(['cart' => $cart]);

        return redirect()->route('cart.index');
        
    }

    public function index() {
        $cart = session()->get('cart', []);
        $cartItems = [];

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }

        return view('cart', [
            'cart' => $cartItems,
        ]);
    }

    public function destroy()
    {
        session()->forget('cart');
        return redirect()->route('cart.index');
    }
}
