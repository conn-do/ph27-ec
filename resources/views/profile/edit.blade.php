@extends('layouts.base')

@section('title', 'プロフィール変更')

@section('content')

    <h2>プロフィール変更</h2>

    @if (session('message'))
        <article>
            {{ session('message') }}
        </article>
    @endif

    @if ($errors->any())
        <article class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </article>
    @endif

    <form action="/profile/edit" method="POST">

        @csrf
        @method('PUT')

        <label>
            名前
            <input
                type="text"
                name="name"
                value="{{ old('name', $user->name) }}"
                required
            >
        </label>

        <label>
            メールアドレス
            <input
                type="email"
                name="email"
                value="{{ old('email', $user->email) }}"
                required
            >
        </label>

        <label>
            新しいパスワード
            <input
                type="password"
                name="password"
                placeholder="変更しない場合は空欄"
            >
        </label>

        <label>
            新しいパスワード（確認）
            <input
                type="password"
                name="password_confirmation"
                placeholder="変更しない場合は空欄"
            >
        </label>

        <button type="submit">
            プロフィールを変更
        </button>

    </form>

    <a href="/mypage">
        マイページに戻る
    </a>

@endsection