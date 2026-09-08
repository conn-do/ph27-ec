@extends('layouts.base')

@section('title', '商品一覧')

@section('content')
    <h1>商品一覧</h1>
    <section aria-label="カテゴリー">
        <h2>カテゴリー</h2>
        <ul class="category-links">
            @foreach ($categories as $category)
                <li><a href="{{ route('categories.show', $category) }}">{{ $category->name }}</a></li>
            @endforeach
        </ul>
    </section>
    <form action="{{ route('home') }}" method="GET" class="search-form">
        <label for="keyword">キーワード</label>
        <input id="keyword" type="search" name="keyword" value="{{ $keyword }}">
        <label for="category">カテゴリー</label>
        <select id="category" name="category">
            <option value="">すべて</option>
            @foreach ($categories as $category)
                <option value="{{ $category->slug }}" @selected($categorySlug === $category->slug)>{{ $category->name }}</option>
            @endforeach
        </select>
        <button type="submit">検索</button>
    </form>
    @if ($keyword !== '' || $categorySlug !== '')
        <a href="{{ route('home') }}">検索結果をクリア</a>
    @endif
    <section class="catalog-list" aria-label="商品">
        @forelse ($products as $product)
            <article class="catalog-item">
                <a href="{{ route('products.show', $product) }}"><img src="{{ $product->imageUrl() }}" width="200" height="200" alt="{{ $product->name }}"></a>
                <div>
                    <h2><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h2>
                    <p>{{ number_format($product->price) }}円</p>
                    @include('products.stock', ['product' => $product])
                    <a href="{{ route('products.show', $product) }}">商品詳細・在庫を見る</a>
                    <form action="{{ route('cart.store') }}" method="POST" class="catalog-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" @disabled($product->stock <= 0)>カートに入れる</button>
                    </form>
                </div>
            </article>
        @empty
            <p>条件に合う商品が見つかりませんでした。</p>
        @endforelse
    </section>
    <section class="news-section">
        <h2>お知らせ</h2>
        @foreach ($news as $item)
            <article class="news-item">
                <h3><a href="{{ route('news.show', $item) }}">{{ $item->title }}</a></h3>
                <p>{{ Str::limit(strip_tags($item->content), 80) }}</p>
            </article>
        @endforeach
    </section>
@endsection
