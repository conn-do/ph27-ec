@extends('layouts.base')

@section('title', '会員登録')

@section('head')
    @vite(['resources/css/register.css'])
@endsection

@section('content')
    <h2>会員登録</h2>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <article>{{ $error }}</article>
        @endforeach
    @endif

    <form action="{{ route('register.store') }}" method="post">
        @csrf
        <label for="name">名前</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
            @if ($errors->has('name')) style="border-color: red;" @endif>

        <label for="email">メールアドレス</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email"
            @if ($errors->has('email')) style="border-color: red;" @endif>

        <label for="password">パスワード</label>
        <input type="password" id="password" name="password" required autocomplete="new-password"
            @if ($errors->has('password')) style="border-color: red;" @endif>

        <label for="password_confirmation">パスワード（確認）</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
            @if ($errors->has('password_confirmation')) style="border-color: red;" @endif>

        <button type="submit">登録する</button>
    </form>

    <p>すでにアカウントをお持ちの方は <a href="{{ route('login') }}">ログイン</a></p>
@endsection
