<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

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

        $this->cart->put($validated['productId'], $validated['quantity']);

        $request->session()->flash('message', 'カートに追加しました。');

        return redirect('/cart');
    }

    public function index()
    {
        // [1 => 2, 2 => 3] （商品ID => 個数）
        $cart = $this->cart->items();
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

    public function update(Request $request, int $productId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ], [
            'quantity.min' => '1個以上選択してください。',
            'quantity.max' => '10個以下を選択してください。',
        ]);

        $product = Product::findOrFail($productId);

        if ($validated['quantity'] > $product->stock) {
            return back()->withErrors([
                'quantity' => '在庫が不足しています。',
            ])->withInput();
        }

        if (! array_key_exists($productId, $this->cart->items())) {
            abort(404);
        }

        $this->cart->put($productId, $validated['quantity']);

        $request->session()->flash('message', 'カートを更新しました。');

        return redirect('/cart');
    }

    public function destroy(Request $request, int $productId)
    {
        $this->cart->remove($productId);

        $request->session()->flash('message', '商品をカートから削除しました。');

        return redirect('/cart');
    }

    public function clear(Request $request)
    {
        $this->cart->clear();
        $request->session()->flash('message', 'カートを空にしました。');

        return redirect('/cart');
    }
}
