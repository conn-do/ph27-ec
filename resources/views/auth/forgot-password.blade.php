@extends('layouts.base')

@section('title', 'パスワード再設定')

@section('content')
    <h2>パスワード再設定</h2>

    <p>登録済みのメールアドレスを入力してください。パスワード再設定用のリンクをお送りします。</p>

    @if (session('status'))
        <article>{{ session('status') }}</article>
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <article>{{ $error }}</article>
        @endforeach
    @endif

    <form action="{{ route('password.email') }}" method="post">
        @csrf
        <label for="email">メールアドレス</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
            @if ($errors->has('email')) style="border-color: red;" @endif>

        <button type="submit">再設定リンクを送信</button>
    </form>

    <p><a href="{{ route('login') }}">ログインに戻る</a></p>
@endsection
