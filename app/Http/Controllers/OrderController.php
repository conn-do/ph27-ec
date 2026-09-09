<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if ($cart === []) {
            return to_route('cart.index')->with('message', 'カートに商品を追加してから購入してください。');
        }

        try {
            Validator::make(['cart' => $cart], [
                'cart' => ['required', 'array', 'min:1'],
                'cart.*' => ['required', 'integer', 'min:1', 'max:10'],
            ])->validate();

            $order = DB::transaction(function () use ($cart, $request): Order {
                $products = Product::query()
                    ->whereKey(array_keys($cart))
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                if ($products->count() !== count($cart)) {
                    throw ValidationException::withMessages([
                        'cart' => '販売終了の商品が含まれています。カートを確認してください。',
                    ]);
                }

                $totalPrice = collect($cart)->sum(
                    fn (int|string $quantity, int|string $productId): int => $products->get($productId)->price * (int) $quantity,
                );

                $order = Order::query()->create([
                    'user_id' => $request->user()->id,
                    'total_price' => $totalPrice,
                ]);

                foreach ($cart as $productId => $quantity) {
                    $product = $products->get($productId);

                    if ((int) $quantity > $product->stock) {
                        throw ValidationException::withMessages([
                            'cart' => '在庫が不足しています。カートの数量を確認してください。',
                        ]);
                    }

                    $order->details()->create([
                        'product_id' => $productId,
                        'quantity' => (int) $quantity,
                    ]);
                    $product->decrement('stock', (int) $quantity);
                }

                return $order;
            });
        } catch (ValidationException $exception) {
            return to_route('cart.index')
                ->withErrors($exception->errors())
                ->with('message', 'カートの商品と数量を確認してください。');
        }

        $request->session()->forget('cart');

        return to_route('orders.show', $order)->with('message', 'ご注文を受け付けました。');
    }

    public function index(Request $request): View
    {
        return view('orders.index', [
            'orders' => $request->user()->orders()->latest()->get(),
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        return view('orders.show', [
            'order' => $order->load('details.product'),
        ]);
    }
}
