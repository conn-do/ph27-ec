<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request, Product $product): RedirectResponse
    {
        $user = $request->user();

        $favorite = $user->favorites()->where('product_id', $product->id)->first();

        if ($favorite) {
            $favorite->delete();
            $request->session()->flash('message', 'お気に入りを解除しました。');
        } else {
            $user->favorites()->create([
                'product_id' => $product->id,
            ]);

            $request->session()->flash('message', 'お気に入りに追加しました。');
        }

        return redirect()->back();
    }
}
