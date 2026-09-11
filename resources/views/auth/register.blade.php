@extends('layouts.base')

@section('title', '会員登録')

@section('content')

    <section class="register-page">

        <div class="register-heading">
            <h2>REGISTER</h2>
            <p>会員登録</p>
        </div>

        <div class="register-content">

            @if ($errors->any())
                <div class="register-error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="post" class="register-form">

                <div class="register-field">
                    <label for="name">
                        名前
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                    >
                </div>

                <div class="register-field">
                    <label for="email">
                        メールアドレス
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="@error('email') error @enderror"
                        value="{{ old('email') }}"
                    >
                </div>

                @error('email')
                    <div class="register-field-error">
                        {{ $message }}
                    </div>
                @enderror

                <div class="register-field">
                    <label for="password">
                        パスワード
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                    >
                </div>

                <div class="register-field">
                    <label for="password_confirmation">
                        パスワード（確認）
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                    >
                </div>

                <button type="submit" class="register-submit">
                    登録する
                </button>

            </form>

            <div class="register-login">
                <p>すでにアカウントをお持ちの方</p>

                <a href="/login">
                    ログインはこちら
                    <span>→</span>
                </a>
            </div>

        </div>

    </section>

@endsection