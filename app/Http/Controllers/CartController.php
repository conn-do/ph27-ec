<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Stripe\Stripe;
use Stripe\Checkout\Session;

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

        // セッションから現在のカートデータを取得（無ければ空配列）
        $cart = session()->get('cart', []);
        
        // 商品IDと数量を取得
        $productId = $validated['productId'];
        $quantity = (int)$validated['quantity'];

        // 既存の数量があれば加算、無ければ新規設定
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
        // セッションから [商品ID => 数量] の連想配列を取得
        $cart = session()->get('cart', []);
        $items = [];
        $totalPrice = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            // 商品が存在する場合のみカート一覧に追加
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

    public function clear(Request $request)
    {
        session()->forget('cart');
        $request->session()->flash('message', 'カートを空にしました。');
        return redirect('/cart');
    }

    public function reorder(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $quantity = (int)$request->input('quantity', 1);

        $cart = session()->get('cart', []);

        // 既存のカートに存在する場合は数量を加算
        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        session()->put('cart', $cart);

        $request->session()->flash('message', "「{$product->name}」をカートに追加しました。");

        return redirect('/cart');
    }
    
}