<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'quantity' => ['required', 'integer', 'min:1']
        ],
        [
            'quantity.required' => '個数は必須です',
            'quantity.integer' => '数字で入力してください',
            'quantity.min' => '正しい個数を入れて下さい',
        ],
    );
        $productId = $request->input('productId');
        $quantity = $request->input('quantity');

        $cart = $request->session()->get('cart', []);
        $cart[$productId] = $quantity;
        session(['cart' => $cart]);


        return redirect()->route('cart.index');
    }


    public function index()
    {
        $cart = session('cart', []);

        $items = [];
        $totalPrice = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            $totalPrice += $product->price * $quantity;
            $item = [
                'product' => $product,
                'quantity' => $quantity,
            ];
            $items [] = $item;
        }

        return view('cart', [
            'items' => $items,
            'totalPrice' => $totalPrice,
        ]);
    }

    public function destroy()
    {
        session()->forget('cart');
        return redirect()->route('cart.index');
    }
}
