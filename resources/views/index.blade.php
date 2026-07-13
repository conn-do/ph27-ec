@extends('layouts.base')

@section('title', '商品一覧')

@section('content')
    <section class="categories">
        <h2>カテゴリー</h2>
        <ul>
            @foreach ($categories as $category)
                <li>{{ $category->name }}</li>
            @endforeach
        </ul>
    </section>

    <section class="products">
        <h2>商品一覧</h2>

        <form action="/search" method="GET">
            <input type="text" name="keyword" value="{{ request('keyword') }}">
            <input type="submit" value="検索">
        </form>

        @if (request('keyword'))
            <a href="{{ route('home') }}">検索結果をクリア</a>
        @endif

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
    </section>

    <section class="news-section">
        <h2 class="news-title">NEWS</h2>
        <p class="news-subtitle">お知らせ</p>

        <div class="news-list">
            @foreach ($news as $item)
                <article class="news-item">
                    <h3 class="news-item-title">
                        <a href="{{ route('news.show', $item) }}">
                            {{ $item->title }}
                        </a>
                    </h3>

                    <p class="news-item-body">
                        {{ Str::limit(strip_tags($item->content), 80) }}
                    </p>
                </article>
            @endforeach
        </div>
    </section>
@endsection
