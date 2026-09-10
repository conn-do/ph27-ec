@extends('layouts.base')

@section('title', '商品一覧')

@section('content')

    {{-- カテゴリ一覧 --}}
    <section class="category-section">
        <h3>カテゴリ</h3>

        <ul class="category-list">
            @foreach ($categories as $category)
                <li>
                    <a href="/categories/{{ $category->slug }}">
                        {{ $category->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </section>

    <section class="ranking-section">

        <div class="section-heading">
            <p class="section-label">RANKING</p>
            <h2>人気ランキング</h2>
        </div>

        @if ($ranking->isEmpty())

            <p class="empty-message">
                まだランキングはありません。
            </p>
        @else
            <div class="ranking-grid">

                @foreach ($ranking as $index => $item)
                    <a href="/products/{{ $item->product->id }}" class="ranking-card">

                        <div class="ranking-number">
                            {{ $index + 1 }}
                        </div>

                        <div class="ranking-image">
                            <img src="{{ $item->product->imageUrl() }}" alt="{{ $item->product->name }}">
                        </div>

                        <div class="ranking-info">
                            <h3>{{ $item->product->name }}</h3>
                            <p>{{ $item->total_quantity }}個販売</p>
                        </div>

                    </a>
                @endforeach

            </div>

        @endif

    </section>


    {{-- 商品一覧 --}}
    <section class="product-section">

        <div class="section-heading">
            <p class="section-label">PRODUCTS</p>
            <h2>商品一覧</h2>
        </div>

        <form class="search-form" action="/search" method="GET">
            <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="商品を検索...">
            <input type="submit" value="検索">
        </form>

        @if (request('keyword'))
            <a class="clear-search" href="/">検索結果をクリア</a>
        @endif


        <div class="product-grid">

            @foreach ($products as $product)
                <article class="product-card">

                    <a href="/products/{{ $product->id }}">

                        <div class="product-image">
                            <img src="{{ $product->imageUrl() }}" alt="{{ $product['name'] }}">
                        </div>

                        <div class="product-info">
                            <h3>{{ $product['name'] }}</h3>
                            <span>VIEW PRODUCT →</span>
                        </div>

                    </a>

                </article>
            @endforeach

        </div>

    </section>


    {{-- お知らせ --}}
    <section class="news-section">

        <div class="section-heading">
            <p class="section-label">INFORMATION</p>
            <h2 class="news-title">NEWS</h2>
            <h3 class="news-subtitle">お知らせ</h3>
        </div>

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

    </section>

@endsection
