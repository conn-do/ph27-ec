@extends('layouts.base')

@section('title', 'ログイン')

@section('content')
    <div style="max-width: 480px; margin: 2rem auto;">
        <article style="padding: 2rem; border-radius: 8px;">
            <h2 style="text-align: center; margin-bottom: 1.5rem; font-size: 1.5rem;">ログイン</h2>

            {{-- エラーメッセージ表示 --}}
            @if ($errors->any())
                <div style="background-color: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.8rem 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                    <ul style="margin: 0; padding-left: 1.2rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" style="margin: 0;">
                @csrf

                <div style="margin-bottom: 1.2rem;">
                    <label for="email" style="font-weight: bold; margin-bottom: 0.4rem; display: block; font-size: 0.95rem;">
                        メールアドレス
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Email" style="margin: 0;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="password" style="font-weight: bold; margin-bottom: 0.4rem; display: block; font-size: 0.95rem;">
                        パスワード
                    </label>
                    <input type="password" id="password" name="password" required placeholder="••••••••" style="margin: 0;">
                </div>

                <button type="submit" style="width: 100%; padding: 0.6rem 0; font-weight: bold; font-size: 1rem; margin-bottom: 1.2rem;">
                    ログイン
                </button>
            </form>

            <div style="text-align: center; border-top: 1px solid var(--pico-muted-border-color); padding-top: 1rem; margin-top: 0.5rem; font-size: 0.9rem;">
                アカウントをお持ちでない方は <a href="{{ route('register') }}" style="font-weight: bold;">会員登録はこちら</a>
            </div>
        </article>
    </div>
@endsection