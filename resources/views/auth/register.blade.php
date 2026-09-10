@extends('layouts.base')

@section('title', '会員登録')

@section('content')
    <div class="container">
        <div class="auth-card">
            <h1 class="auth-title">会員登録</h1>

            @if ($errors->any())
                <div class="error-box">
                    @foreach ($errors->all() as $error)
                        <article class="error">{{ $error }}</article>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="post" class="auth-form">
                @csrf
                <div class="form-group">
                    <label>名前</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label>メールアドレス</label>
                    <input type="email" name="email" class="@error('email') error @enderror" value="{{ old('email') }}"
                        required>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>パスワード</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>パスワード（確認）</label>
                    <input type="password" name="password_confirmation" required>
                </div>
                <button type="submit" class="buy-btn auth-submit-btn">登録</button>
            </form>

            <div class="auth-link-area">
                <a href="/login">ログインはこちら</a>
            </div>
        </div>
    </div>
@endsection
