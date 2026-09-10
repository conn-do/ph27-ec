@extends('layouts.base')

@section('title', 'プロフィール編集')

@section('content')

    <div class="profile-container">

        <div class="profile-card">

            <h1 class="profile-title">
                プロフィール編集
            </h1>

            <form action="/profile/update" method="POST">
                @csrf

                <div class="form-group">
                    <label>名前</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}">
                </div>

                <div class="form-group">
                    <label>メールアドレス</label>
                    <input type="email" name="email" value="{{ auth()->user()->email }}">
                </div>

                <button type="submit" class="profile-btn">
                    保存する
                </button>

            </form>

        </div>

    </div>

@endsection
