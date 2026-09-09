@extends('layouts.base')

@section('title', '会員登録')

@section('content')
    <h1>会員登録</h1>
    @if ($errors->any())
        <div class="registration-errors" role="alert">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif
    <form action="{{ route('register.store') }}" method="POST" class="auth-form" novalidate>
        @csrf
        <div class="form-field">
            <label for="name">名前:</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" autocomplete="name" required @if($errors->has('name')) aria-invalid="true" aria-describedby="name-error" @endif>
            @error('name')<p class="field-error" id="name-error">{{ $message }}</p>@enderror
        </div>
        <div class="form-field">
            <label for="email">メールアドレス:</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required @if($errors->has('email')) aria-invalid="true" aria-describedby="email-error" @endif>
            @error('email')<p class="field-error" id="email-error">{{ $message }}</p>@enderror
        </div>
        <div class="form-field">
            <label for="password">パスワード:</label>
            <input id="password" type="password" name="password" autocomplete="new-password" required @if($errors->has('password')) aria-invalid="true" aria-describedby="password-error" @endif>
            @error('password')<p class="field-error" id="password-error">{{ $message }}</p>@enderror
        </div>
        <div class="form-field">
            <label for="password_confirmation">パスワード（確認）:</label>
            <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" required>
        </div>
        <button type="submit">登録</button>
    </form>
    <a href="{{ route('login') }}">ログインはこちら</a>
@endsection
