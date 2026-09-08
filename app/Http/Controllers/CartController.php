<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Request $request)
    {
        // 入力チェックをして、OKならフォームの入力値を取得
        $validated = $request->validate([
            'productId' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10',
        ], [
            'quantity.min' => '1個以上選択してください。',
            'quantity.max' => '10個以下を選択してください。',
        ]);

        $product = Product::findOrFail($validated['productId']);

        if ($validated['quantity'] > $product->stock) {
            return back()->withErrors([
                'quantity' => '在庫が不足しています。',
            ])->withInput();
        }
        // セッションにカートの内容を保存
        $cart = session()->get('cart', []);
        // [1 => 2, 2 => 3] （商品ID => 個数）
        $cart[$validated['productId']] = $validated['quantity'];
        session()->put('cart', $cart);

        $request->session()->flash('message', 'カートに追加しました。');

        return redirect('/cart');
    }

    public function index()
    {
        // [1 => 2, 2 => 3] （商品ID => 個数）
        $cart = session()->get('cart', []);
        $items = [];
        $totalPrice = 0;
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
            $totalPrice += $product->price * $quantity;
        }

        return view('cart', [
            'items' => $items,
            'totalPrice' => $totalPrice, // 合計金額
        ]);
    }

    public function clear(Request $request)
    {
        session()->forget('cart');
        $request->session()->flash('message', 'カートを空にしました。');

        return redirect('/cart');
    }
}
