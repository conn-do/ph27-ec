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
    <div class="news-section">
        <h3>最新情報</h3>
        <ul class="news-list">
            @foreach ($news as $newsItem)
                <li>
                    <a href="/news/{{ $newsItem->id }}">{{ $newsItem->title }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
