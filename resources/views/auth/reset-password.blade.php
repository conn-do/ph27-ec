@extends('layouts.base')

@section('title', '新しいパスワードの設定')

@section('content')
    <h2>新しいパスワードの設定</h2>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <article>{{ $error }}</article>
        @endforeach
    @endif

    <form action="{{ route('password.update') }}" method="post">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <label for="email">メールアドレス</label>
        <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required readonly
            autocomplete="email" @if ($errors->has('email')) style="border-color: red;" @endif>

        <label for="password">新しいパスワード</label>
        <input type="password" id="password" name="password" required autofocus autocomplete="new-password"
            @if ($errors->has('password')) style="border-color: red;" @endif>

        <label for="password_confirmation">新しいパスワード（確認）</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required
            autocomplete="new-password"
            @if ($errors->has('password_confirmation')) style="border-color: red;" @endif>

        <button type="submit">パスワードを変更する</button>
    </form>

    <p><a href="{{ route('login') }}">ログインに戻る</a></p>
@endsection
