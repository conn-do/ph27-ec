@extends('layouts.base')
@section('title', $product->name)
@section('content')
    <nav class="breadcrumbs" aria-label="パンくず">
        <a href="{{ route('home') }}">
            ホーム
        </a>
        <span>
            /
        </span>
        @if($product->category)
            <a href="{{ route('categories.show', $product->category) }}">
                {{ $product->category->name }}
            </a>
            <span>
                /
            </span>
        @endif
        <span>
            {{ $product->name }}
        </span>
    </nav>
    <section class="product-detail">
        <div class="detail-image">
            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" width="900" height="900">
        </div>
        <div class="detail-copy">
            <p class="eyebrow">
                {{ $product->category?->name }} / YOHAKU SELECTION
            </p>
            <h1>
                {{ $product->name }}
            </h1>
            <p class="detail-price">
                ¥{{ number_format($product->price) }}
                <small>
                    税込
                </small>
            </p>
            <p class="description">
                {{ $product->description }}
            </p>
            <p class="stock-status">
                {{ $product->stock === 0 ? '○ ただいま品切れです' : ($product->stock < 5 ? '● 残り'.$product->stock.'点' : '● 在庫あり') }}
            </p>
            <form action="{{ route('cart.store') }}" method="post" class="add-to-cart" data-submit>
                @csrf
                <input type="hidden" name="productId" value="{{ $product->id }}">
                <label for="quantity">
                    数量
                    <small>
                        1商品につき最大{{ config('shop.max_quantity') }}個
                    </small>
                </label>
                <div class="add-row">
                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" max="{{ min($product->stock, config('shop.max_quantity')) }}" required @disabled($product->
                    stock === 0)
                    @error('quantity')
                        aria-invalid="true" aria-describedby="quantity-error"
                    @enderror
                    >
                    <button class="button" type="submit" @disabled($product->
                        stock === 0)>{{ $product->stock === 0 ? '売り切れ' : 'カートに入れる' }}
                        <span aria-hidden="true">
                            ＋
                        </span>
                    </button>
                </div>
                @error('quantity')
                    <p class="field-error" id="quantity-error">
                        {{ $message }}
                    </p>
                @enderror
            </form>
            <p class="delivery-note">
                送料 ¥{{ number_format(config('shop.shipping_fee')) }} / ¥{{ number_format(config('shop.free_shipping_threshold')) }}以上で送料無料
                <br>
                学習用デモのため、実際の決済・配送は行われません。
            </p>
        </div>
    </section>
    @if($related->isNotEmpty())
        <section class="collection">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">
                        YOU MAY ALSO LIKE
                    </p>
                    <h2>
                        こちらも、いっしょに。
                    </h2>
                </div>
            </div>
            <div class="product-grid">
                @foreach($related as $item)
                    <x-product-card :product="$item" />
                @endforeach
            </div>
        </section>
    @endif
@endsection
