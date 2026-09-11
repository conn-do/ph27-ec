@extends('layouts.base')

@section('title', 'お気に入り')

@section('content')

    <section class="favorites-page">
        <div class="product-page-heading">
            <h2>お気に入り</h2>
        </div>

        @if ($favorites->isEmpty())
            <div class="favorites-empty">
                <p>お気に入りの商品がありません。</p>
            </div>
        @else
            <div class="product-list">
                @foreach ($favorites as $favorite)
                    <article class="product-item">
                        <a href="/products/{{ $favorite->product->id }}">
                            <div class="product-image">
                                <img
                                    src="{{ $favorite->product->imageUrl() }}"
                                    alt="{{ $favorite->product->name }}"
                                >
                            </div>

                            <h3>{{ $favorite->product->name }}</h3>

                            <p>
                                ¥{{ number_format($favorite->product->price) }}
                            </p>
                        </a>

                        <div class="favorite-actions">
                            <form action="/favorites" method="POST">
                                @csrf

                                <input
                                    type="hidden"
                                    name="productId"
                                    value="{{ $favorite->product->id }}"
                                >

                                <button type="submit">
                                    お気に入りから削除
                                </button>
                            </form>

                            <form action="/cart" method="POST" class="favorite-cart">
                                @csrf

                                <input
                                    type="hidden"
                                    name="productId"
                                    value="{{ $favorite->product->id }}"
                                >

                                <input
                                    type="number"
                                    name="quantity"
                                    value="1"
                                    min="1"
                                    max="10"
                                >

                                <input
                                    type="submit"
                                    value="カートに入れる"
                                >
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

     <div class="ranking-space">
        <div class="ranking-space-column"></div>
        <div class="ranking-space-column"></div>
        <div class="ranking-space-column"></div>
    </div>

@endsection