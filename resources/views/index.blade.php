@extends('layouts.base')

@section('title', '商品一覧')

@section('content')

    <h2>商品一覧</h2>

    <form action="/search" method="GET">
        <input type="text" name="keyword" value="{{ request('keyword') }}">
        <input type="submit" value="検索">
    </form>

        {{-- News 最新3件 --}}
    @isset($news)
        <section class="news">
            <h2>News</h2>

            @foreach ($news as $item)
                <div class="news-item">
                    <h3>{{ $item->title }}</h3>

                    <a href="/news/{{ $item->id }}">
                        詳細を見る
                    </a>
                </div>
            @endforeach
        </section>
    @endisset

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

@endsection