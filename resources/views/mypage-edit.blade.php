@extends('layouts.base')

@section('title', 'プロフィール編集')

@section('content')

    <div class="mypage-page">

        {{-- =========================
         Header
    ========================= --}}

        <section class="mypage-header">

            <p class="mypage-label">
                PH27 STATIONERY / MEMBERS
            </p>

            <h1 class="mypage-title">
                PROFILE EDIT
            </h1>

            <p class="mypage-subtitle">
                アカウント情報を変更できます。
            </p>

        </section>


        {{-- =========================
         Error
    ========================= --}}

        @if ($errors->any())

            <div class="mypage-errors">

                @foreach ($errors->all() as $error)
                    <p>
                        {{ $error }}
                    </p>
                @endforeach

            </div>

        @endif


        {{-- =========================
         Edit Form
    ========================= --}}

        <form action="/mypage" method="POST" class="mypage-edit-form">

            @csrf
            @method('PUT')


            {{-- 名前 --}}

            <div class="mypage-edit-field">

                <label for="name" class="mypage-edit-label">
                    NAME
                </label>

                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                    autocomplete="name" required>

            </div>


            {{-- メールアドレス --}}

            <div class="mypage-edit-field">

                <label for="email" class="mypage-edit-label">
                    EMAIL ADDRESS
                </label>

                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                    autocomplete="email" required>

            </div>


            {{-- 保存 --}}

            <button type="submit" class="mypage-save-button">
                SAVE CHANGES
            </button>

        </form>


        {{-- =========================
         Back
    ========================= --}}

        <div class="mypage-edit-back">

            <a href="/mypage" class="mypage-back-link">
                ← BACK TO MY PAGE
            </a>

        </div>

    </div>

@endsection
