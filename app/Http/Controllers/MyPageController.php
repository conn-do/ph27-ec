<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateDeliveryAddressRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MyPageController extends Controller
{
    public function index(): View
    {
        return view('mypage');
    }

    public function updateDeliveryAddress(UpdateDeliveryAddressRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        return to_route('mypage')->with('message', '配送先情報を更新しました。');
    }
}
