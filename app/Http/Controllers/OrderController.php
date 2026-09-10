<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if ($cart === []) {
            throw ValidationException::withMessages([
                'cart' => 'カートに商品を追加してから注文してください。',
            ]);
        }

        $products = Product::query()
            ->whereKey(array_keys($cart))
            ->get()
            ->keyBy('id');

        foreach ($cart as $productId => $quantity) {
            if (! $products->has((int) $productId) || ! is_int($quantity) || $quantity < 1 || $quantity > 10) {
                throw ValidationException::withMessages([
                    'cart' => 'カートの内容が変更されています。内容を確認してください。',
                ]);
            }
        }

        $totalPrice = collect($cart)->sum(
            fn (int $quantity, int|string $productId): int => $products->get((int) $productId)->price * $quantity,
        );

        $order = DB::transaction(function () use ($cart, $products, $request, $totalPrice): Order {
            $order = Order::query()->create([
                'total_price' => $totalPrice,
                'user_id' => $request->user()->id,
            ]);

            foreach ($cart as $productId => $quantity) {
                OrderDetail::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }

            return $order;
        });

        session()->forget('cart');

        return redirect()->route('orders.complete', $order)->with('message', '注文が完了しました！');
    }

    public function complete(Request $request, Order $order): View
    {
        abort_unless($order->user()->is($request->user()), 403);

        return view('orders.complete', [
            'order' => $order,
        ]);
    }
}
