<?php

namespace App\Http\Controllers;

use App\Actions\Shop\Cart;
use App\Actions\Shop\CartCalculator;
use App\Http\Requests\Shop\StoreCartItemRequest;
use App\Http\Requests\Shop\UpdateCartItemRequest;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    public function __construct(
        private readonly Cart $cart,
        private readonly CartCalculator $calculator,
    ) {}

    public function index(): View
    {
        $this->cart->removeMissingProducts();

        return view('cart.index', [
            'calculation' => $this->calculator->calculate(),
        ]);
    }

    public function store(StoreCartItemRequest $request): RedirectResponse
    {
        $product = Product::findOrFail($request->validated('product_id'));

        if (! $product->isInStock()) {
            return back()->with('error', "「{$product->name}」は いま うりきれだよ。");
        }

        $this->cart->add($product, (int) $request->validated('quantity'));

        return redirect()
            ->route('cart.index')
            ->with('message', "「{$product->name}」を カートに いれたよ！");
    }

    public function update(UpdateCartItemRequest $request, Product $product): RedirectResponse
    {
        $this->cart->update($product, (int) $request->validated('quantity'));

        return redirect()
            ->route('cart.index')
            ->with('message', 'かずを かえたよ。');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->cart->remove($product->id);

        return redirect()
            ->route('cart.index')
            ->with('message', "「{$product->name}」を カートから だしたよ。");
    }

    public function clear(): RedirectResponse
    {
        $this->cart->clear();

        return redirect()
            ->route('cart.index')
            ->with('message', 'カートを からっぽに したよ。');
    }
}
