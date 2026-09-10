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
            'quantity.max' => '10個以下を選択してください。',
        ]);

        $productId = $validated['productId'];
        $quantity = $validated['quantity'];

        // セッションから現在のカート情報を取得
        $cart = session()->get('cart', []);

        // すでにカートに入っている場合は個数を加算し、なければ新しく設定
        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        // セッションに保存
        session()->put('cart', $cart);

        $request->session()->flash('message', 'カートに追加しました。');

        return redirect('/cart');
    }


    public function index()
    {
        // [商品ID => 個数] の配列を取得
        $cart = session()->get('cart', []);

        $items = [];
        $totalPrice = 0;

        foreach ($cart as $productId => $quantity) {

            $product = Product::find($productId);

            if ($product) {

                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                ];

                $totalPrice += $product->price * $quantity;
            }
        }

        return view('cart', [
            'items' => $items,
            'totalPrice' => $totalPrice,
        ]);
    }


    // カート内の商品の数量を変更する
    public function update(Request $request, $productId)
    {
        // 数量の入力チェック
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ], [
            'quantity.required' => '数量を入力してください。',
            'quantity.integer' => '数量は整数で入力してください。',
            'quantity.min' => '1個以上を指定してください。',
            'quantity.max' => '10個以下を指定してください。',
        ]);

        // 現在のカートを取得
        $cart = session()->get('cart', []);

        // カート内にその商品がある場合だけ数量を変更
        if (isset($cart[$productId])) {

            $cart[$productId] = $validated['quantity'];

            // セッションに保存
            session()->put('cart', $cart);

            $request->session()->flash(
                'message',
                '数量を変更しました。'
            );
        }

        return redirect('/cart');
    }


    // 特定の1商品を削除するメソッド
    public function destroy(Request $request, $productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {

            unset($cart[$productId]);

            session()->put('cart', $cart);
        }

        $request->session()->flash(
            'message',
            'カート内の商品を削除しました。'
        );

        return redirect('/cart');
    }


    // カートを空にする
    public function clear(Request $request)
    {
        session()->forget('cart');

        $request->session()->flash(
            'message',
            'カートを空にしました。'
        );

        return redirect('/cart');
    }
}