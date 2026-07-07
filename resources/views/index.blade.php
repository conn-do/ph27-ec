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

    <h2>NEWS</h2>

    <div class="news-list">
        @foreach ($news as $item)
            <div class="news-item">
                <a href="/news/{{ $item->id }}" class="news-title">
                    {{ $item->title }}
                </a>

                <p class="news-content">
                    {{ Str::limit(strip_tags($item->content), 40) }}
                </p>
            </div>
        @endforeach
    </div>

@endsection
