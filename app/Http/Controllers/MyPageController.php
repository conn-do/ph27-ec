<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyPageController extends Controller
{
    // マイページを表示
    public function index()
    {
        // 現在ログインしているユーザーを取得
        $user = Auth::user();

        // 現在のユーザーの注文履歴を新しい順で取得
        $orders = $user->orders()
            ->latest()
            ->get();

        // マイページにユーザー情報と注文履歴を渡す
        return view('mypage', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }


    // プロフィール編集画面を表示
    public function edit()
    {
        // 現在ログインしているユーザーを取得
        $user = Auth::user();

        return view('mypage-edit', [
            'user' => $user,
        ]);
    }


    // プロフィールを更新
    public function update(Request $request)
    {
        // 現在ログインしているユーザーを取得
        $user = Auth::user();

        // 入力内容をチェック
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ], [
            'name.required' => '名前を入力してください。',
            'name.max' => '名前は255文字以内で入力してください。',
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '正しいメールアドレスを入力してください。',
            'email.max' => 'メールアドレスは255文字以内で入力してください。',
            'email.unique' => 'そのメールアドレスはすでに使用されています。',
        ]);

        // ユーザー情報を更新
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // DBに保存
        $user->save();

        // マイページへ戻る
        return redirect('/mypage')
            ->with('message', 'プロフィールを更新しました。');
    }
}