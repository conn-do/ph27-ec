@extends('layouts.base')
@section('title', 'ログイン')
@section('content')
    <section class="auth-panel">
        <p class="eyebrow">
            WELCOME BACK
        </p>
        <h1>
            おかえりなさい。
        </h1>
        <p class="muted">
            ログインして、お買い物の続きを。
        </p>
        @if(session('status'))
            <p role="status">
                {{ session('status') }}
            </p>
        @endif
        <form action="{{ route('login.store') }}" method="post" data-submit>
            @csrf
            <div class="form-field">
                <label for="email">
                    メールアドレス
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="username" required @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                @error('email')
                    <p id="email-error" class="field-error">
                        {{ $message }}
                    </p>
                @enderror
            </div>
            <div class="form-field">
                <label for="password">
                    パスワード
                </label>
                <input type="password" id="password" name="password" autocomplete="current-password" required>
            </div>
            <div class="auth-options">
                <label>
                    <input type="checkbox" name="remember" value="1">
                    ログイン状態を保持
                </label>
                <a href="{{ route('password.request') }}">
                    パスワードを忘れた方
                </a>
            </div>
            <button class="button full-width" type="submit">
                ログイン →
            </button>
        </form>
        <div class="auth-switch">
            はじめてのお客様
            <a href="{{ route('register') }}">
                会員登録はこちら ↗
            </a>
        </div>
    </section>
@endsection
