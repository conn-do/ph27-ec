<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if ($cart === []) {
            return to_route('cart.index')->withErrors([
                'cart' => 'カートに商品がありません。',
            ]);
        }

        $products = Product::query()->whereKey(array_keys($cart))->get()->keyBy('id');

        if ($products->count() !== count($cart)) {
            return to_route('cart.index')->withErrors([
                'cart' => 'カート内の商品を確認できません。',
            ]);
        }

        $items = collect($cart)
            ->map(fn (int $quantity, int|string $productId): array => [
                'subtotal' => $products->get($productId)->price * $quantity,
                'product' => $products->get($productId),
                'quantity' => $quantity,
            ])
            ->values();

        return view('checkout', [
            'items' => $items,
            'paymentMethods' => PaymentMethod::availableForCheckout(),
            'totalPrice' => $items->sum('subtotal'),
        ]);
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $cart = $request->session()->get('cart', []);

        if ($cart === []) {
            throw ValidationException::withMessages([
                'cart' => 'カートに商品がありません。',
            ]);
        }

        $order = DB::transaction(function () use ($cart, $request, $validated): Order {
            $products = Product::query()
                ->whereKey(array_keys($cart))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($products->count() !== count($cart)) {
                throw ValidationException::withMessages([
                    'cart' => 'カート内の商品を確認できません。',
                ]);
            }

            foreach ($cart as $productId => $quantity) {
                if ($quantity > $products->get($productId)->stock) {
                    throw ValidationException::withMessages([
                        'cart' => '在庫数が不足している商品があります。',
                    ]);
                }
            }

            $order = Order::create([
                'total_price' => collect($cart)->sum(
                    fn (int $quantity, int|string $productId): int => $products->get($productId)->price * $quantity,
                ),
                'user_id' => $request->user()->id,
                'shipping_name' => $validated['shipping_name'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_postal_code' => $validated['shipping_postal_code'],
                'shipping_address' => $validated['shipping_address'],
                'payment_method' => $validated['payment_method'],
            ]);

            foreach ($cart as $productId => $quantity) {
                $product = $products->get($productId);

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);

                $product->decrement('stock', $quantity);
            }

            return $order;
        });

        $request->session()->forget('cart');
        $request->session()->flash('message', '注文が完了しました。');

        return to_route('orders.show', $order);
    }

    public function index(Request $request): View
    {
        return view('orders.index', [
            'orders' => $request->user()->orders()
                ->with('details.product')
                ->latest()
                ->get(),
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        $order = $request->user()->orders()
            ->with('details.product')
            ->findOrFail($order->id);

        return view('orders.show', [
            'order' => $order,
        ]);
    }
}
