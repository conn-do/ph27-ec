@extends('layouts.base')

@section('title', '会員登録')

@section('content')
    <section class="auth-page" aria-labelledby="register-heading">
        <div class="auth-card">
            <h1 id="register-heading">会員登録</h1>
            <p class="auth-intro">必要事項を入力して、会員登録を完了してください。</p>

            @if ($errors->any())
                <div class="auth-errors" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form class="auth-form" action="{{ route('register.store') }}" method="post">
                @csrf
                <div class="auth-field">
                    <label for="name">お名前</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" autocomplete="name"
                        required>
                </div>
                <div class="auth-field">
                    <label for="email">メールアドレス</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email"
                        required>
                </div>
                <div class="auth-field">
                    <label for="password">パスワード</label>
                    <input id="password" type="password" name="password" autocomplete="new-password" required>
                </div>
                <div class="auth-field">
                    <label for="password_confirmation">パスワード（確認）</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        autocomplete="new-password" required>
                </div>
                <button class="auth-submit" type="submit">登録する</button>
            </form>

            <p class="auth-switch">すでに会員登録済みですか？ <a href="{{ route('login', absolute: false) }}">ログインはこちら</a></p>
        </div>
    </section>
@endsection
