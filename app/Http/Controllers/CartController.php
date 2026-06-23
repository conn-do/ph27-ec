<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // 1. 顯示購物車內容
    public function index()
    {
        $cart = session()->get('cart', []);
        $items = [];
        $totalPrice = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
                $totalPrice += $product->price * $quantity;
            }
        }

        return view('cart', compact('items', 'totalPrice'));
    }

    // 🟢 2. 補上這個：處理「加入購物車」的 POST 請求
    public function store(Request $request)
    {
        // 驗證傳過來的商品 ID 和數量
        $validated = $request->validate([
            'productId' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        // 取出目前的購物車 Session，如果沒有就給空陣列
        $cart = session()->get('cart', []);

        $productId = $validated['productId'];
        $quantity = $validated['quantity'];

        // 如果購物車已經有這件商品，數量累加；沒有的話就新增
        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        // 存回 Session
        session()->put('cart', $cart);

        // 跳轉回購物車頁面，並附帶成功訊息
        return redirect('/cart')->with('message', 'カートに追加しました。');
    }

    // 3. 清空購物車
    public function clear()
    {
        session()->forget('cart');
        return redirect('/cart')->with('message', 'カートを空にしました。');
    }
}