@extends('layouts.base')

@section('title', 'ログイン')

@section('content')

    <section class="login-page">

        <div class="login-heading">
            <h2>LOGIN</h2>
            <p>ログイン</p>
        </div>

        <div class="login-content">

            @if ($errors->any())
                <div class="login-error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login') }}" method="post" class="login-form">
                @csrf

                <div class="login-field">
                    <label for="email">
                        メールアドレス
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                    >
                </div>

                <div class="login-field">
                    <label for="password">
                        パスワード
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                    >
                </div>

                <button type="submit" class="login-submit">
                    ログイン
                </button>
            </form>

            <div class="login-register">
                <p>アカウントをお持ちでない方</p>

                <a href="/register">
                    会員登録はこちら
                    <span>→</span>
                </a>
            </div>

        </div>

    </section>

@endsection