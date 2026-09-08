@extends('layouts.base')

@section('title', '会員登録')

@section('content')

<div style="max-width: 600px; margin: 60px auto; color: #e5e7eb; padding: 0 20px;">

    <h2 style="font-size: 20px; font-weight: 600; color: #e5e7eb; margin-top: 0; margin-bottom: 30px; border-left: 4px solid #38bdf8; padding-left: 12px;">
        会員登録
    </h2>

    {{-- エラーメッセージ --}}
    @if ($errors->any())
        <div style="margin-bottom: 25px; border-left: 3px solid #f87171; padding-left: 12px;">
            @foreach ($errors->all() as $error)
                <div style="color: #fca5a5; font-size: 13px; margin-bottom: 4px;">
                    {{ $error }}
                </div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('register.store') }}" method="POST">
        @csrf

        <div style="margin-bottom: 25px;">
            <label style="display: block; font-size: 13px; color: #9ca3af; margin-bottom: 8px;">名前</label>
            <input type="text" name="name" value="{{ old('name') }}" required style="width: 100%; background: #13161E; border: 1px solid #2a3245; color: #fff; padding: 12px 16px; border-radius: 4px; font-size: 14px; outline: none; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; font-size: 13px; color: #9ca3af; margin-bottom: 8px;">メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}" required style="width: 100%; background: #13161E; border: 1px solid #2a3245; color: #fff; padding: 12px 16px; border-radius: 4px; font-size: 14px; outline: none; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; font-size: 13px; color: #9ca3af; margin-bottom: 8px;">パスワード</label>
            <input type="password" name="password" required style="width: 100%; background: #13161E; border: 1px solid #2a3245; color: #fff; padding: 12px 16px; border-radius: 4px; font-size: 14px; outline: none; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 35px;">
            <label style="display: block; font-size: 13px; color: #9ca3af; margin-bottom: 8px;">パスワード（確認）</label>
            <input type="password" name="password_confirmation" required style="width: 100%; background: #13161E; border: 1px solid #2a3245; color: #fff; padding: 12px 16px; border-radius: 4px; font-size: 14px; outline: none; box-sizing: border-box;">
        </div>

        <div>
            <button type="submit" style="padding: 10px 28px; background: transparent; color: #38bdf8; border: 1px solid #38bdf8; font-size: 14px; cursor: pointer; border-radius: 4px; transition: all 0.2s;">
                登録する
            </button>
        </div>
    </form>

    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #2a3245; font-size: 13px;">
        <a href="/login" style="color: #38bdf8; text-decoration: none;">すでにアカウントをお持ちの方はこちら →</a>
    </div>

</div>

@endsection