@extends('layouts.base')

@section('title', '注文完了')

@section('content')
    <h2>注文完了</h2>
    @if (session('message'))
        <article>
            {{ session('message') }}
        </article>
    @endif
    <a href="{{ route('home') }}">買い物を続ける</a>
@endsection
