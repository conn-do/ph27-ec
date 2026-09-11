<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255',
        ]);

        $coupon = Coupon::where('code', strtoupper($validated['code']))->first();

        if (! $coupon || ! $coupon->isValid()) {
            return redirect('/cart')->with('message', 'このクーポンコードは利用できません。');
        }

        session()->put('coupon_code', $coupon->code);

        return redirect('/cart')->with('message', 'クーポンを適用しました。');
    }

    public function destroy(Request $request): RedirectResponse
    {
        session()->forget('coupon_code');

        return redirect('/cart')->with('message', 'クーポンを解除しました。');
    }
}
