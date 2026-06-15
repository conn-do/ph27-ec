@extends('layouts.base')

@section('title', '会員登録')

@section('content')
    <h1>会員登録</h1>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <article>{{ $error }}</article>
        @endforeach
    @endif
    <form action="{{ route('register.store') }}" method="post">
        <div>
            名前:
            <input type="text" name="name">
        </div>
        <div>
            メールアドレス:
            <input type="email" name="email" class="@error('email') error @enderror" value="{{ old('email') }}">
        </div>
        @error('email')
            <div class="error">{{ $message }}</div>
        @enderror
        <div>
            パスワード:
            <input type="password" name="password">
        </div>
        <div>
            パスワード（確認）:
            <input type="password" name="password_confirmation">
        </div>
        <button type="submit">登録</button>
    </form>
    <a href="/login">ログインはこちら</a>
@endsection
