@extends('layouts.base')

@section('title', '会員登録')

@section('content')

    <section class="register-page">

        <div class="register-heading">
            <p>CREATE ACCOUNT</p>
            <h1>会員登録</h1>
        </div>

        @if ($errors->any())
            <div class="register-errors">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="register-box">

            <form action="{{ route('register.store') }}" method="post">
                @csrf

                <div class="form-group">
                    <label for="name">名前</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}">
                </div>

                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" id="email" name="email" class="@error('email') error @enderror"
                        value="{{ old('email') }}">
                </div>

                @error('email')
                    <div class="register-field-error">{{ $message }}</div>
                @enderror

                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" id="password" name="password">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">パスワード（確認）</label>
                    <input type="password" id="password_confirmation" name="password_confirmation">
                </div>

                <button type="submit" class="register-button">
                    登録
                </button>
            </form>

            <div class="login-link">
                <p>すでにアカウントをお持ちの方</p>
                <a href="/login">ログインはこちら</a>
            </div>

        </div>

    </section>

@endsection
