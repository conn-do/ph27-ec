<?php

namespace App\Http\Controllers;

use App\Exceptions\OrderPlacementException;
use App\Http\Requests\PlaceOrderRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderPlacementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    private const TOKEN_KEY = 'paperloop.checkout_token';

    public function index(Request $request, CartService $cart): Response|RedirectResponse
    {
        $summary = $cart->summary();

        if ($summary['items'] === []) {
            return to_route('cart.index')->with('error', 'カートに商品を追加してから購入手続きへ進んでください。');
        }

        $token = Str::uuid()->toString();
        $request->session()->put(self::TOKEN_KEY, $token);

        return Inertia::render('checkout/index', [
            'cart' => $summary,
            'checkoutToken' => $token,
        ]);
    }

    public function store(
        PlaceOrderRequest $request,
        CartService $cart,
        OrderPlacementService $orders,
    ): RedirectResponse {
        $token = $request->session()->pull(self::TOKEN_KEY);

        if (! is_string($token) || ! hash_equals($token, $request->string('checkout_token')->toString())) {
            return to_route('checkout.index')->with('error', '注文情報の有効期限が切れました。もう一度お試しください。');
        }

        try {
            $order = $orders->place(
                $request->user(),
                $cart->quantitiesForCheckout(),
                $request->safe()->only(['customer_name', 'postal_code', 'address']),
            );
        } catch (OrderPlacementException $exception) {
            return to_route('checkout.index')->with('error', $exception->getMessage());
        }

        $cart->clear();

        return to_route('checkout.complete', ['order' => $order]);
    }

    public function complete(Request $request, Order $order): Response
    {
        abort_unless($order->user_id === $request->user()->id, 404);

        return Inertia::render('checkout/complete', [
            'order' => $order->load('items:id,order_id,product_name,quantity,price'),
        ]);
    }
}
