@extends('layouts.base')

@section('title', 'マイページ')

@section('content')
    <a href="/orders">注文履歴</a>

    <a href="/address">配送先住所</a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit">ログアウト</button>
    </form>
@endsection