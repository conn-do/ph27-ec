@extends('layouts.base')

@section('title', '商品一覧')

@section('content')

    {{-- カテゴリ一覧 --}}
    <h3>カテゴリ</h3>
    <ul>
        @foreach ($categories as $category)
            <li>
                <a href="/categories/{{ $category->slug }}">
                    {{ $category->name }}
                </a>
            </li>
        @endforeach
    </ul>

    <h2>商品一覧</h2>

    <form action="/search" method="GET">
        <input type="text" name="keyword" value="{{ request('keyword') }}">
        <input type="submit" value="検索">
    </form>

    @if (request('keyword'))
        <a href="/">検索結果をクリア</a>
    @endif

    {{-- 商品一覧 --}}
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

    {{-- お知らせ --}}
    <h2 class="news-title">NEWS</h2>
    <h3 class="news-subtitle">お知らせ</h3>

    <div class="news-list">
        @foreach ($news as $item)
            <div class="news-item">

                <h4 class="news-item-title">
                    <a href="/news/{{ $item->id }}">
                        {{ $item->title }}
                    </a>
                </h4>

                <p class="news-item-body">
                    {!! $item->content !!}
                </p>

            </div>
        @endforeach
    </div>

@endsection
