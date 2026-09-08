@extends('layouts.base')

@section('title', '商品一覧')

@section('content')
    <p class="section-heading">Category</p>
    <ul class="category-list">
        @foreach ($categories ?? [] as $category)
            <li>
                <a href="/categories/{{ $category->slug }}">
                    {{ $category->name }}
                </a>
            </li>
        @endforeach
    </ul>

    <h1 class="page-title">商品一覧</h1>

    <form class="search-form" action="/search" method="GET">
        <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="商品名で検索">
        <input type="submit" value="検索">
    </form>

    @if (request('keyword'))
        <a class="search-clear" href="/">検索結果をクリア</a>
    @endif

    <div class="product-grid">
        @forelse ($products as $product)
            <a class="product-card" href="/products/{{ $product->id }}">
                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">
                <span class="product-card-body">
                    <span class="product-card-name">{{ $product->name }}</span>
                    <span class="product-card-price">{{ number_format($product->price) }}円</span>
                </span>
            </a>
        @empty
            <p class="empty-note">商品が見つかりませんでした。</p>
        @endforelse
    </div>

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
