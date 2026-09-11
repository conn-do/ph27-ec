<?php

namespace App\Http\Controllers;

use App\Actions\Shop\Cart;
use App\Actions\Shop\CartCalculator;
use App\Actions\Shop\ChangeCalculator;
use App\Actions\Shop\PlaceOrder;
use App\Http\Requests\Shop\StoreCheckoutRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * レジ（おかいけい）の画面。
 *
 * 「いくらになるか」を計算式のまま見せて、
 * じぶんで お金をえらんで はらう練習をする。
 */
class CheckoutController extends Controller
{
    public function __construct(
        private readonly Cart $cart,
        private readonly CartCalculator $calculator,
        private readonly ChangeCalculator $changeCalculator,
    ) {}

    public function show(): View|RedirectResponse
    {
        $this->cart->removeMissingProducts();
        $calculation = $this->calculator->calculate();

        if ($calculation['lines'] === []) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'カートが からっぽだよ。');
        }

        return view('checkout.show', [
            'calculation' => $calculation,
            'denominations' => config('shop.denominations'),
            'taxLabels' => config('shop.tax.labels'),
        ]);
    }

    public function store(StoreCheckoutRequest $request, PlaceOrder $placeOrder): RedirectResponse
    {
        $order = $placeOrder->handle($request->user(), (int) $request->validated('paid_amount'));

        return redirect()->route('orders.complete', $order);
    }
}
