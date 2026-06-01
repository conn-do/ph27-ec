@extends('layouts.base')

@section('title', 'ログイン')

@section('content')
    <h2>ログイン</h2>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <article>{{ $error }}</article>
        @endforeach
    @endif

    <form action="{{ route('login.store') }}" method="post">
        @csrf
        <label for="email">メールアドレス</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
            @if ($errors->has('email')) style="border-color: red;" @endif>

        <label for="password">パスワード</label>
        <input type="password" id="password" name="password" required autocomplete="current-password"
            @if ($errors->has('password')) style="border-color: red;" @endif>

        <label>
            <input type="checkbox" name="remember">
            ログイン状態を保持する
        </label>

        <button type="submit">ログイン</button>
    </form>

    {{-- <p><a href="{{ route('password.request') }}">パスワードをお忘れですか？</a></p> --}}

    <p>アカウントをお持ちでない方は <a href="{{ route('register') }}">会員登録</a></p>
@endsection
