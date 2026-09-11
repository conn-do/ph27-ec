<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MyPageController extends Controller
{
    /**
     * マイページ。おこづかい・買った回数・おきにいりの数をひとめで。
     */
    public function index(Request $request): View
    {
        $user = $request->user()
            ->loadCount(['orders', 'favorites', 'reviews'])
            ->loadSum('orders', 'total_price');

        return view('mypage', [
            'user' => $user,
            'recentOrders' => $request->user()
                ->orders()
                ->orderByDesc('created_at')
                ->limit(3)
                ->get(),
        ]);
    }
}
