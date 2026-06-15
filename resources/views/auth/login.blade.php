@extends('layouts.base')

@section('title', 'ログイン')

@section('content')
    <h1>ログイン</h1>
    <form action="{{ route('login') }}" method="post">
        <div>
            メールアドレス:
            <input type="email" name="email">
        </div>
        <div>
            パスワード:
            <input type="password" name="password">
        </div>
        <button type="submit">ログイン</button>
    </form>
    <a href="/register">会員登録はこちら</a>
@endsection
