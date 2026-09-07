<?php

namespace App\Http\Controllers;

use App\Actions\CartContents;
use App\Actions\PlaceOrder;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class OrderController extends Controller
{
    public function create(Request $request, CartContents $contents): View|RedirectResponse
    {
        $cart = $contents->handle($request);
        if ($cart['items'] === []) {
            return to_route('cart.index')->withErrors(['cart' => '商品をカートに追加してください。']);
        }
        if (! $request->session()->has('checkout_token')) {
            $request->session()->put('checkout_token', (string) Str::uuid());
        }

        return view('orders.checkout', $cart);
    }

    public function store(CheckoutRequest $request, PlaceOrder $placeOrder): RedirectResponse
    {
        $existing = $request->user()->orders()->where('checkout_token', $request->validated('checkout_token'))->first();
        if ($existing) {
            return to_route('orders.show', $existing);
        }
        if ($request->validated('checkout_token') !== $request->session()->get('checkout_token')) {
            return to_route('cart.index')->withErrors(['cart' => '注文画面の有効期限が切れました。もう一度お進みください。']);
        }
        try {
            $order = $placeOrder->handle($request->user(), $request->session()->get('cart', []), $request->validated());
        } catch (ValidationException $exception) {
            return to_route('cart.index')->withErrors($exception->errors());
        } catch (Throwable $exception) {
            Log::error('Checkout could not be saved.', ['exception_type' => $exception::class]);

            return to_route('cart.index')->withErrors(['cart' => '注文を保存できませんでした。時間をおいてもう一度お試しください。']);
        }
        $request->session()->forget(['cart', 'checkout_token']);

        return to_route('orders.show', $order)->with('message', 'ご注文ありがとうございます。注文を受け付けました。');
    }

    public function index(Request $request): View
    {
        return view('orders.index', ['orders' => $request->user()->orders()->latest('id')->paginate(10)]);
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 404);

        return view('orders.show', ['order' => $order->load('details.product')]);
    }
}
