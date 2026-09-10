<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function store(Request $request)
    {
        // 入力チェックをして、OKならフォームの入力値を取得
        $validated = $request->validate([
            'productId' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:10',
        ], [
            'quantity.min' => '1個以上選択してください。',
            'quantity.max' => '10個以下の選択してください。',
        ]);

        $product = Product::find($validated['productId']);

        if ($product->stock <= 0) {
            return redirect('/products/' . $product->id)
                ->with('message', 'この商品は売り切れです。');
        }

        if ($validated['quantity'] > $product->stock) {
            return redirect('/products/' . $product->id)
                ->with('message', '在庫数を超えています。');
        }

        // セッションにカートの内容を保存
        $cart = session()->get('cart', []);
        // [1 => 2, 2 => 3] （商品ID => 個数）
        $cart[$validated['productId']] = $validated['quantity'];
        session()->put('cart', $cart);

        $request->session()->flash('message', 'カートに追加しました。');

        return redirect('/cart');
    }

    public function index(Request $request)
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

        $shippingFee = 500;

        if ($request->user()) {
            $address = $request->user()->address;

            if ($address) {
                if (str_contains($address->address, '北海道')) {
                    $shippingFee = 1000;
                } elseif (str_contains($address->address, '沖縄')) {
                    $shippingFee = 1500;
                }
            }
        }

        $grandTotal = $totalPrice + $shippingFee;

        return view('cart', [
            'items' => $items,
            'totalPrice' => $totalPrice,
            'shippingFee' => $shippingFee,
            'grandTotal' => $grandTotal,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'productId' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:10',
        ], [
            'quantity.min' => '1個以上選択してください。',
            'quantity.max' => '10個以下の選択してください。',
        ]);

        $product = Product::find($validated['productId']);

        if ($validated['quantity'] > $product->stock) {
            return redirect('/cart')
                ->with('message', '在庫数を超えています。');
        }

        $cart = session()->get('cart', []);

        $cart[$validated['productId']] = $validated['quantity'];

        session()->put('cart', $cart);

        return redirect('/cart');
    }

    public function remove(Request $request)
    {
        $productId = $request->productId;

        $cart = session()->get('cart', []);

        unset($cart[$productId]);

        session()->put('cart', $cart);

        return redirect('/cart');
    }

    public function clear(Request $request)
    {
        session()->forget('cart');
        $request->session()->flash('message', 'カートを空にしました。');

        return redirect('/cart');
    }
}