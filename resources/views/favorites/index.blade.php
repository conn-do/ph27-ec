@extends('layouts.base')

@section('title', 'お気に入り一覧')

@section('content')
    @if (session('message'))
        <article>{!! session('message') !!}</article>
    @endif
    <h1>お気に入り一覧</h1>
    @forelse ($favorites as $favorite)
        <ul>
            <li>
                <a href="/products/{{ $favorite->product->id }}">
                    {{ $favorite->product->name }}
                    <img src="{{ $favorite->product->imageUrl() }}" width="200">
                </a>
                <a href="/favorites/remove/{{ $favorite->product->id }}">お気に入り解除</a>
            </li>
        </ul>
    @empty
        <p>お気に入りに登録された商品はありません。</p>
    @endforelse
@endsection