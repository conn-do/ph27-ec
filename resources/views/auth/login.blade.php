@extends('layouts.base')

@section('title', 'ログイン')

@section('content')

    <div class="auth-page">

        <div class="auth-inner">

            {{-- =========================
             Heading
        ========================= --}}

            <div class="auth-header">

                <p class="auth-label">
                    PH27 STATIONERY / MEMBERS
                </p>

                <h1 class="auth-title">
                    LOGIN
                </h1>

                <p class="auth-subtitle">
                    アカウントにログインしてください。
                </p>

            </div>


            {{-- =========================
             Errors
        ========================= --}}

            @if ($errors->any())

                <div class="auth-errors">

                    @foreach ($errors->all() as $error)
                        <p>
                            {{ $error }}
                        </p>
                    @endforeach

                </div>

            @endif


            {{-- =========================
             Login Form
        ========================= --}}

            <form action="{{ route('login') }}" method="POST" class="auth-form">

                @csrf


                {{-- Email --}}

                <div class="auth-field">

                    <label for="email" class="auth-field-label">
                        EMAIL ADDRESS
                    </label>

                    <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email"
                        required>

                </div>


                {{-- Password --}}

                <div class="auth-field">

                    <label for="password" class="auth-field-label">
                        PASSWORD
                    </label>

                    <input type="password" id="password" name="password" autocomplete="current-password" required>

                </div>


                {{-- Login --}}

                <button type="submit" class="auth-submit">
                    LOGIN
                </button>

            </form>


            {{-- =========================
             Register
        ========================= --}}

            <div class="auth-register">

                <p class="auth-register-text">
                    アカウントをお持ちでない方
                </p>

                <a href="/register" class="auth-register-link">
                    CREATE AN ACCOUNT
                </a>

            </div>


            {{-- =========================
             Back
        ========================= --}}

            <div class="auth-back">

                <a href="/" class="auth-back-link">
                    ← BACK TO CATALOG
                </a>

            </div>

        </div>

    </div>

@endsection
