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

        // セッションにカートの内容を保存
        $cart = session()->get('cart', []);
        // [1 => 2, 2 => 3] （商品ID => 個数）
        $cart[$validated['productId']] = $validated['quantity'];
        session()->put('cart', $cart);
    }
}
