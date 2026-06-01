@extends('layouts.base')

@section('title', 'マイページ')

@section('content')
    <h2>マイページ</h2>
    <form action="{{ route('logout') }}" method="post">
        @csrf
        <button type="submit">ログアウト</button>
    </form>
@endsection
