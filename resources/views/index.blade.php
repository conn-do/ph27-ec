@extends('layouts.base')

@section('title', '商品一覧')

@section('content')
    <h2>商品一覧</h2>
    <form action="/search" method="GET">
        <input type="text" name="keyword" value="{{ request('keyword') }}">
        <input type="submit" value="検索">
    </form>
    @if (request('keyword'))
        <a href="/">検索結果をクリア</a>
    @endif
    @foreach ($products as $product)
        <ul>
            <li>
                <a href="/products/{{ $product->id }}">
                    {{ $product['name'] }}
                    <img src="{{ $product->imageUrl() }}" width="200">
                </a>
            </li>
        </ul>
    @endforeach
    <div class="news-container">
        <h3 class="news-title">最新情報</h3>
        <ul class="news-list">
            @foreach ($news as $item)
                <li class="news-item">
                    <a href="/news/{{ $item->id }}" class="news-link">{{ $item->title }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
