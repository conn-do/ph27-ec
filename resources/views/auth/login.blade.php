@extends('layouts.base')

@section('title', 'ログイン')

@section('content')
    <h1>ログイン</h1>
    @if ($errors->any())
        <div class="form-errors" role="alert">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif
    <form action="{{ route('login.store') }}" method="POST" class="auth-form" novalidate>
        @csrf
        <div class="form-field">
            <label for="email">メールアドレス:</label>
            <input id="email" type="email" name="email" autocomplete="username" value="{{ old('email') }}" required @if($errors->has('email')) aria-invalid="true" @endif>
        </div>
        <div class="form-field">
            <label for="password">パスワード:</label>
            <input id="password" type="password" name="password" autocomplete="current-password" required @if($errors->has('password')) aria-invalid="true" @endif>
        </div>
        <button type="submit">ログイン</button>
    </form>
    <a href="{{ route('register') }}">会員登録はこちら</a>
@endsection
