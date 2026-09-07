@extends('layouts.base')
@section('title', '会員登録')
@section('content')
    <section class="auth-panel">
        <p class="eyebrow">
            HELLO, NEW FRIEND.
        </p>
        <h1>
            余白へ、ようこそ。
        </h1>
        <p class="muted">
            アカウントをつくって、お気に入りを日常に。
        </p>
        <form action="{{ route('register.store') }}" method="post" data-submit>
            @csrf
            @foreach(['name' => ['お名前', 'text', 'name'], 'email' => ['メールアドレス', 'email', 'username'], 'password' => ['パスワード（8文字以上）', 'password', 'new-password'], 'password_confirmation' => ['パスワード（確認）', 'password', 'new-password']] as $field => [$label, $type, $autocomplete])
                <div class="form-field">
                    <label for="{{ $field }}">
                        {{ $label }}
                    </label>
                    <input id="{{ $field }}" type="{{ $type }}" name="{{ $field }}" @if($type !== 'password') value="{{ old($field) }}" @else minlength="8" @endif autocomplete="{{ $autocomplete }}" required @error($field) aria-invalid="true" aria-describedby="{{ $field }}-error" @enderror>
                    @error($field)
                        <p class="field-error" id="{{ $field }}-error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            @endforeach
            <button class="button full-width" type="submit">
                アカウントを作成 →
            </button>
        </form>
        <div class="auth-switch">
            すでにアカウントをお持ちの方
            <a href="{{ route('login') }}">
                ログイン ↗
            </a>
        </div>
    </section>
@endsection
