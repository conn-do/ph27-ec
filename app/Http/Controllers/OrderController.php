<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function store(Request $request): View
    {
        $cart = $request->session()->get('cart', []);
        if ($cart === []) {
            throw ValidationException::withMessages(['cart' => 'カートに商品がありません。']);
        }

        $order = DB::transaction(function () use ($cart, $request): Order {
            $products = Product::whereIn('id', array_keys($cart))->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $totalPrice = 0;
            foreach ($cart as $productId => $quantity) {
                $product = $products->get($productId);
                if (! $product || ! is_int($quantity) || $quantity < 1 || $quantity > 10 || $quantity > $product->stock) {
                    throw ValidationException::withMessages(['cart' => '商品の在庫または数量を確認して、カートを更新してください。']);
                }
                $totalPrice += $product->price * $quantity;
            }

            $order = new Order;
            $order->total_price = $totalPrice;
            $order->user_id = $request->user()->id;
            $order->save();
            foreach ($cart as $productId => $quantity) {
                $detail = new OrderDetail;
                $detail->order_id = $order->id;
                $detail->product_id = $productId;
                $detail->quantity = $quantity;
                $detail->save();
                $products->get($productId)->decrement('stock', $quantity);
            }

            return $order;
        });

        $request->session()->forget('cart');

        return view('orders.complete', compact('order'));
    }

    public function index(Request $request): View
    {
        return view('orders.index', ['orders' => $request->user()->orders()->latest()->get()]);
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        $order->load('details.product');

        return view('orders.show', compact('order'));
    }
}
