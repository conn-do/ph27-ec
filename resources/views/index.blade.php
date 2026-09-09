@extends('layouts.base')

@section('title', '商品一覧')

@section('content')
    <section class="store-hero" aria-label="2026 秋コレクション">
        <img src="{{ asset('images/hero/autumn-2026.jpg') }}" alt="2026 秋コレクション、新作アイテム入荷のお知らせ">
    </section>

    <section class="storefront-section" aria-labelledby="catalog-heading">
        <h1 id="catalog-heading" class="storefront-section-title">商品一覧</h1>

        <div class="catalog-layout">
            <aside class="catalog-sidebar" aria-label="カテゴリ">
                <h2>カテゴリ</h2>
                <ul class="catalog-categories">
                    <li>
                        <a class="is-active" href="{{ route('home') }}#catalog-heading">すべて</a>
                    </li>
                    @foreach ($categories as $category)
                        <li>
                            <a href="{{ route('categories.show', $category) }}">
                                {{ $category->name }} ({{ $category->products_count }})
                            </a>
                        </li>
                    @endforeach
                </ul>
            </aside>

            <div class="catalog-products">
                <div class="catalog-toolbar">
                    <form class="catalog-search" action="/search" method="GET">
                        <input type="search" name="keyword" value="{{ request('keyword') }}" placeholder="商品を検索">
                        <button type="submit">検索</button>
                    </form>

                    @if (request('keyword'))
                        <a class="catalog-clear" href="/">検索結果をクリア</a>
                    @endif
                </div>

                <div class="catalog-meta-row">
                    <span>全{{ $products->count() }}件</span>
                </div>

                <div class="product-grid">
                    @foreach ($products as $product)
                        <article class="product-card">
                            <a href="{{ route('products.show', $product) }}">
                                <img class="product-card-image" src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">
                                <div class="product-card-body">
                                    <h2 class="product-card-name">{{ $product->name }}</h2>
                                    <div class="product-card-meta">
                                        <span class="product-card-price">¥{{ number_format($product->price) }}</span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="news" class="storefront-section store-news" aria-labelledby="news-heading">
        <h2 id="news-heading" class="storefront-section-title">お知らせ</h2>

        <div class="news-list">
            @foreach ($news as $item)
                <article class="news-item">
                    <time
                        datetime="{{ $item->created_at?->toDateString() }}">{{ $item->created_at?->format('Y.m.d') }}</time>
                    <div class="news-item-content">
                        <h3 class="news-item-title">
                            <a href="/news/{{ $item->id }}">
                                {{ $item->title }}
                            </a>
                        </h3>

                        <div class="news-item-body">
                            {!! $item->content !!}
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

@endsection
