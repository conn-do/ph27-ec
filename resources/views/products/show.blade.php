@extends('layouts.base')

@section('title', $product->name)

@section('content')
    <section class="product-detail" aria-labelledby="product-heading">
        <div class="product-detail-layout">
            <img class="product-detail-image" src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">
            <div class="product-detail-content">
                <a class="product-detail-category" href="{{ route('categories.show', $product->category) }}">
                    {{ $product->category->name }}
                </a>
                <h1 id="product-heading">{{ $product->name }}</h1>
                <p class="product-detail-price">¥{{ number_format($product->price) }}</p>
                <p class="product-detail-description">{{ $product->description }}</p>

                @if ($product->stock <= 0)
                    <p class="stock-status is-sold-out">在庫なし</p>
                @elseif ($product->stock <= 5)
                    <p class="stock-status is-low">残り{{ $product->stock }}点</p>
                @else
                    <p class="stock-status">在庫あり</p>
                @endif

                @if ($product->stock > 0)
                    <form class="add-to-cart-form" action="{{ route('cart.store') }}" method="POST">
                        @csrf
                        <label for="quantity">数量</label>
                        <div class="add-to-cart-actions">
                            <input id="quantity" type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" max="{{ $product->stock }}">
                            <input type="hidden" name="productId" value="{{ $product->id }}">
                            <button type="submit">カートに入れる</button>
                        </div>
                        @error('quantity')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </form>
                @endif
            </div>
        </div>
    </section>

    @if ($relatedProducts->isNotEmpty())
        <section class="related-products storefront-section" aria-labelledby="related-products-heading">
            <h2 id="related-products-heading" class="storefront-section-title">関連商品</h2>
            <div class="product-grid">
                @foreach ($relatedProducts as $relatedProduct)
                    <article class="product-card">
                        <a href="{{ route('products.show', $relatedProduct) }}">
                            <img class="product-card-image" src="{{ $relatedProduct->imageUrl() }}" alt="{{ $relatedProduct->name }}">
                            <div class="product-card-body">
                                <h3 class="product-card-name">{{ $relatedProduct->name }}</h3>
                                <span class="product-card-price">¥{{ number_format($relatedProduct->price) }}</span>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
@endsection
