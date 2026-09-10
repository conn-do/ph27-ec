@extends('layouts.base')

@section('title', 'マイページ')

@section('content')

    <h2>マイページ</h2>

    <p>
        <a href="/orders">注文履歴</a>
    </p>

    <p>
        <a href="{{ route('profile.edit') }}">
            プロフィール変更
        </a>
    </p>

@endsection