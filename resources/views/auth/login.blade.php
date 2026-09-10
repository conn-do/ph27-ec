@extends('layouts.base')

@section('title', 'ログイン')

@section('content')
    <div class="container">
        <div class="auth-card">
            <h1 class="auth-title">ログイン</h1>

            @if ($errors->any())
                <div class="error-box">
                    @foreach ($errors->all() as $error)
                        <article class="error">{{ $error }}</article>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login') }}" method="post" class="auth-form">
                @csrf
                <div class="form-group">
                    <label>メールアドレス</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label>パスワード</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="buy-btn auth-submit-btn">ログイン</button>
            </form>

            <div class="auth-link-area">
                <a href="/register">会員登録はこちら</a>
            </div>
        </div>
    </div>
@endsection
