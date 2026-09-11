<?php

namespace App\Http\Controllers;

use App\Http\Requests\Shop\StoreReviewRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    /**
     * かんそうを書く。買ったことのある商品だけ書ける。
     *
     * @throws ValidationException
     */
    public function store(StoreReviewRequest $request, Product $product): RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasPurchased($product)) {
            throw ValidationException::withMessages([
                'rating' => 'かったことのある しょうひんだけ かんそうを かけるよ。',
            ]);
        }

        $product->reviews()->updateOrCreate(
            ['user_id' => $user->id],
            $request->validated(),
        );

        return redirect()
            ->route('products.show', $product)
            ->with('message', 'かんそうを ありがとう！');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $request->user()->reviews()->where('product_id', $product->id)->delete();

        return redirect()
            ->route('products.show', $product)
            ->with('message', 'かんそうを けしたよ。');
    }
}
