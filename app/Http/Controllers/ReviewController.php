<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // 同じ商品に既にレビューしていた場合は上書きする（1商品につき1件まで）
        Review::updateOrCreate(
            ['user_id' => $request->user()->id, 'product_id' => $product->id],
            $validated,
        );

        return redirect('/products/'.$product->id)->with('message', 'レビューを投稿しました。');
    }

    public function destroy(Request $request, Review $review)
    {
        if ($review->user_id !== $request->user()->id) {
            abort(403);
        }

        $productId = $review->product_id;
        $review->delete();

        return redirect('/products/'.$productId)->with('message', 'レビューを削除しました。');
    }
}
