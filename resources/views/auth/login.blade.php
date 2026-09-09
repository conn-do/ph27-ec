@extends('layouts.base')

@section('title', 'ログイン')

@section('content')
    <section class="auth-page" aria-labelledby="login-heading">
        <div class="auth-card">
            <h1 id="login-heading">ログイン</h1>
            <p class="auth-intro">登録済みのお客様はこちらからログインしてください。</p>

            @if ($errors->any())
                <div class="auth-errors" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form class="auth-form" action="{{ route('login') }}" method="post">
                @csrf
                <div class="auth-field">
                    <label for="email">メールアドレス</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email"
                        required>
                </div>
                <div class="auth-field">
                    <label for="password">パスワード</label>
                    <input id="password" type="password" name="password" autocomplete="current-password" required>
                </div>
                <button class="auth-submit" type="submit">ログイン</button>
            </form>

            <p class="auth-switch">はじめてのお客様ですか？ <a href="{{ route('register', absolute: false) }}">会員登録はこちら</a></p>
        </div>
    </section>
@endsection
