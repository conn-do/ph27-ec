@extends('layouts.base')

@section('title', '検索結果')

@section('content')

    <section class="search-page">
        <div class="product-page-heading">
            <h2>検索結果</h2>
            <p class="product-page-subtitle">
                「{{ request('keyword') }}」の検索結果
            </p>
        </div>

        @if ($products->isEmpty())
            <div class="search-empty">
                <p>該当する商品がありません。</p>
            </div>
        @else
            <div class="product-list">
                @foreach ($products as $product)
                    <article class="product-item">
                        <a href="/products/{{ $product->id }}">
                            <div class="product-image">
                                <img
                                    src="{{ $product->imageUrl() }}"
                                    alt="{{ $product->name }}"
                                >
                            </div>

                            <h3>{{ $product->name }}</h3>

                            <p>
                                ¥{{ number_format($product->price) }}
                            </p>
                        </a>
                    </article>
                @endforeach
            </div>
        @endif

        <div class="search-back">
            <a href="/">商品一覧に戻る</a>
        </div>
    </section>

     <div class="ranking-space">
        <div class="ranking-space-column"></div>
        <div class="ranking-space-column"></div>
        <div class="ranking-space-column"></div>
    </div>

@endsection