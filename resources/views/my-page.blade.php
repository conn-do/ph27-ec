@extends('layouts.base')

@section('title', 'マイページ')

@section('content')
    <h2>マイページ</h2>
    <a href="{{ route('orders.index') }}">注文履歴</a>
    <form action="{{ route('logout') }}" method="post">
        @csrf
        <button type="submit">ログアウト</button>
    </form>
@endsection
