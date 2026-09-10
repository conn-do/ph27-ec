<?php

namespace App\Http\Controllers;

use App\Http\Requests\Settings\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MyPageController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('mypage', [
            'user' => $user,
            'favorites' => $user->favoriteProducts()->orderByPivot('created_at', 'desc')->get(),
            'recentOrders' => $user->orders()->latest()->limit(3)->get(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        return to_route('mypage')->with('message', 'プロフィールを更新しました。');
    }
}
