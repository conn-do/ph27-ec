<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();

        return redirect('/mypage')
            ->with('message', 'プロフィールを更新しました');
    }
}