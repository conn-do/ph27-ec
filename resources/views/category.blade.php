@extends('layouts.base')

@section('title', $category->name)

@section('content')
    <section class="category-page" aria-labelledby="category-heading">
        <div class="category-heading">
            <p>カテゴリー</p>
            <h1 id="category-heading">{{ $category->name }}</h1>
            <span>{{ $category->products->count() }}件</span>
        </div>

        <nav class="category-navigation" aria-label="カテゴリ">
            <a href="{{ route('home') }}#catalog-heading">すべて</a>
            @foreach ($categories as $item)
                <a class="{{ $item->is($category) ? 'is-active' : '' }}" href="{{ route('categories.show', $item) }}">
                    {{ $item->name }} <small>({{ $item->products_count }})</small>
                </a>
            @endforeach
        </nav>

        @if ($category->products->isEmpty())
            <div class="empty-state">
                <p>このカテゴリーにはまだ商品がありません。</p>
                <a href="{{ route('home') }}#catalog-heading">すべての商品を見る</a>
            </div>
        @else
            <div class="product-grid">
                @foreach ($category->products as $product)
                    <article class="product-card">
                        <a href="{{ route('products.show', $product) }}">
                            <img class="product-card-image" src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">
                            <div class="product-card-body">
                                <h2 class="product-card-name">{{ $product->name }}</h2>
                                <span class="product-card-price">¥{{ number_format($product->price) }}</span>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
