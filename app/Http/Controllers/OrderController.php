<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class OrderController extends Controller
{
    public function store(Request $request): RedirectResponse|View
    {
        $cart = $request->session()->get('cart', []);

        if ($cart === []) {
            return to_route('cart.index')->with('message', 'カートに商品がありません。');
        }

        try {
            $order = DB::transaction(function () use ($cart, $request): Order {
                $products = Product::whereKey(array_keys($cart))->lockForUpdate()->get()->keyBy('id');

                if ($products->count() !== count($cart)) {
                    throw new RuntimeException('販売終了の商品が含まれています。');
                }

                $totalPrice = collect($cart)->sum(function (int $quantity, int|string $productId) use ($products): int {
                    $product = $products->get((int) $productId);

                    if ($quantity > $product->stock) {
                        throw new RuntimeException("{$product->name}の在庫が不足しています。");
                    }

                    return $product->price * $quantity;
                });

                $order = Order::create([
                    'total_price' => $totalPrice,
                    'user_id' => $request->user()->id,
                ]);

                foreach ($cart as $productId => $quantity) {
                    $order->details()->create([
                        'product_id' => $productId,
                        'quantity' => $quantity,
                    ]);
                    $products->get((int) $productId)->decrement('stock', $quantity);
                }

                return $order;
            });

            $request->session()->forget('cart');

            return view('orders.complete', [
                'order' => $order,
            ])->with('message', '注文が完了しました！');
        } catch (RuntimeException $exception) {
            return to_route('cart.index')->with('message', $exception->getMessage());
        }
    }

    public function index(Request $request): View
    {
        $orders = $request->user()->orders()->withCount('details')->latest()->get();

        return view('orders.index', [
            'orders' => $orders,
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        $order->load('details.product');

        return view('orders.show', [
            'order' => $order,
        ]);
    }
}
