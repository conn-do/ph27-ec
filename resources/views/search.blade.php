@extends('layouts.base')

@section('title', '検索結果')

@section('content')

    <h2>検索結果</h2>

    <p>「{{ request('keyword') }}」の検索結果</p>

    @if ($products->isEmpty())
        <p>該当する商品がありません。</p>
    @else
        @foreach ($products as $product)
            <ul>
                <li>
                    <a href="/products/{{ $product->id }}">
                        {{ $product->name }}
                        <img src="{{ $product->imageUrl() }}" width="200">
                    </a>
                </li>
            </ul>
        @endforeach
    @endif

    <a href="/">商品一覧に戻る</a>

@endsection