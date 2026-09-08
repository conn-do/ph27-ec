@extends('layouts.base')

@section('title', 'マイページ')

@section('content')
    <h1 class="page-title">マイページ</h1>
    <nav class="mypage-nav">
        <a href="/orders">注文履歴</a>
        <a href="/mypage/favorites">お気に入り一覧</a>
    </nav>
@endsection
