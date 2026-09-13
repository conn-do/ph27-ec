@extends('layouts.base')

@section('title', '商品一覧')

@section('content')
    <section class="hero-section">
        <p class="eyebrow">STATIONERY STORE</p>
        <h1>すごい文房具サイト</h1>
        <p>毎日使いたくなるペン、ノート、鉛筆をそろえました。</p>
    </section>

    <section class="category-section" aria-labelledby="category-title">
        <h2 id="category-title">カテゴリ</h2>
        <div class="category-list">
            @foreach ($categories as $category)
                <a href="/categories/{{ $category->slug }}">{{ $category->name }}</a>
            @endforeach
        </div>
    </section>

    <section aria-labelledby="products-title">
        <div class="section-heading">
            <h2 id="products-title">商品一覧</h2>
            <form class="search-form" action="/search" method="GET">
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="商品名で検索">
                <input type="submit" value="検索">
            </form>
        </div>

        @if (request('keyword'))
            <a class="clear-link" href="/">検索結果をクリア</a>
        @endif

        <div class="product-grid">
            @forelse ($products as $product)
                <article class="product-card">
                    <a href="/products/{{ $product->id }}">
                        <img src="{{ $product->imageUrl() }}" width="200" alt="{{ $product->name }}">
                        <span class="product-name">{{ $product->name }}</span>
                        <span class="product-price">{{ number_format($product->price) }}円</span>
                    </a>
                </article>
            @empty
                <p>商品が見つかりませんでした。</p>
            @endforelse
        </div>
    </section>

    <section class="news-section" aria-labelledby="news-title">
        <p class="eyebrow">NEWS</p>
        <h2 id="news-title" class="news-title">お知らせ</h2>

        <div class="news-list">
            @foreach ($news as $item)
                <article class="news-item">
                    <h3 class="news-item-title">
                        <a href="/news/{{ $item->id }}">{{ $item->title }}</a>
                    </h3>
                    <p class="news-item-body">{!! $item->content !!}</p>
                </article>
            @endforeach
        </div>
    </section>
@endsection
