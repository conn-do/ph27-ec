@extends('layouts.base')

@section('title', 'ログイン')

@section('content')

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>@yield('title') - すごい文房具サイト</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <section class="login-page">

        <div class="login-heading">
            <p>WELCOME BACK</p>
            <h1>ログイン</h1>
        </div>

        @if ($errors->any())
            <div class="login-errors">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="login-box">
            <form action="{{ route('login') }}" method="post">
                @csrf

                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" id="password" name="password">
                </div>

                <button type="submit" class="login-button">
                    ログイン
                </button>
            </form>

            <div class="register-link">
                <p>アカウントをお持ちでない方</p>
                <a href="/register">会員登録はこちら</a>
            </div>
        </div>

    </section>

@endsection
