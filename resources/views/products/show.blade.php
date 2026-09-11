@extends('layouts.base')

@section('title', $product->name)

@section('content')

    <section class="product-detail">
        <div class="product-detail-image">
            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">
        </div>

        <div class="product-detail-info">
            <p class="product-detail-category">
                <a href="/categories/{{ $product->category->slug }}">
                    {{ $product->category->name }}
                </a>
            </p>

            <h2 class="product-detail-title">
                {{ $product->name }}
            </h2>

            <p class="product-detail-description">
                {{ $product->description }}
            </p>

            @auth
                @php
                    $isFavorite = auth()->user()->favorites
                        ->where('product_id', $product->id)
                        ->isNotEmpty();
                @endphp

                <div class="product-detail-favorite">
                    <form action="/favorites" method="POST">
                        @csrf

                        <input
                            type="hidden"
                            name="productId"
                            value="{{ $product->id }}"
                        >

                        <button
                            type="submit"
                            class="{{ $isFavorite ? 'is-favorite' : '' }}"
                        >
                            お気に入り
                        </button>
                    </form>
                </div>
            @endauth

            <div class="product-detail-stock">
                @if (!$product->is_active)
                    <p>販売終了</p>
                @elseif ($product->stock <= 0)
                    <p>売り切れ</p>
                @elseif ($product->stock <= 5)
                    <p>残りわずか（在庫：{{ $product->stock }}個）</p>
                @else
                    <p>在庫：{{ $product->stock }}個</p>
                @endif
            </div>

            @if (session('message'))
                <div class="product-detail-message">
                    <p>{{ session('message') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="product-detail-error">
                    @foreach ($errors->all() as $error)
                        <article class="error">
                            {{ $error }}
                        </article>
                    @endforeach
                </div>
            @endif

            @if ($product->is_active && $product->stock > 0)
                <form action="/cart" method="POST" class="product-detail-cart">
                    @csrf

                    <div class="product-detail-quantity">
                        <div class="quantity-control">
                            <span class="quantity-label">数量</span>

                            <div class="quantity-input">
                                <button
                                    type="button"
                                    class="quantity-button"
                                    id="quantity-minus"
                                >
                                    −
                                </button>

                                <input
                                    type="number"
                                    name="quantity"
                                    id="quantity"
                                    class="@error('quantity') error @enderror"
                                    value="{{ old('quantity', 1) }}"
                                    min="1"
                                    max="10"
                                >

                                <button
                                    type="button"
                                    class="quantity-button"
                                    id="quantity-plus"
                                >
                                    ＋
                                </button>
                            </div>
                        </div>
                    </div>

                    <input
                        type="hidden"
                        name="productId"
                        value="{{ $product->id }}"
                    >

                    <p class="product-detail-price">
                        ¥{{ number_format($product->price) }}
                    </p>

                    <input
                        type="submit"
                        value="カートに入れる"
                    >
                </form>
            @endif
        </div>
    </section>

    <div class="ranking-space">
        <div class="ranking-space-column"></div>
        <div class="ranking-space-column"></div>
        <div class="ranking-space-column"></div>
    </div>

@endsection