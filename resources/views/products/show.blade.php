@extends('layouts.base')

@section('title', $product->name)

@section('content')
<<<<<<< Updated upstream
    <h2>{{ $product->name }}</h2>
    <div>
        カテゴリー:
        <a href="/categories/{{ $product->category->slug }}">
            {{ $product->category->name }}
        </a>
    </div>
    <img src="{{ $product->imageUrl() }}" width="400">
    <p>{{ $product->price }}円</p>
    <p>{{ $product->description }}</p>
    @if ($product->stock <= 0)
        <p>売り切れ</p>
    @elseif ($product->stock <= 5)
        <p>残りわずか</p>
    @else
        <p>在庫あり</p>
    @endif
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <article class="error">
                {{ $error }}
            </article>
        @endforeach
    @endif
    <form action="/cart" method="POST">
        個数:<input type="number" name="quantity" class="@error('quantity') error @enderror" value="{{ old('quantity', 1) }}">
        <input type="hidden" name="productId" value="{{ $product->id }}">
        <input type="submit" value="カートに入れる">
    </form>
=======
    <div class="content-width product-detail">
        <a class="back-link" href="{{ route('home') }}">商品一覧に戻る</a>
        <div class="product-detail__grid">
            <div class="product-detail__image">
                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">
            </div>
            <section class="product-detail__content">
                <p class="eyebrow">{{ $product->category?->name ?? '文房具' }}</p>
                <h1>{{ $product->name }}</h1>
                <p class="price price--large">&yen;{{ number_format($product->price) }}</p>
                <p class="product-description">{{ $product->description }}</p>

                @if ($errors->any())
                    <div class="form-errors" role="alert">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <label for="quantity">数量</label>
                    <div class="add-to-cart-form__controls">
                        <input id="quantity" type="number" name="quantity" min="1" max="10" value="{{ old('quantity', 1) }}">
                        <button class="button button--primary" type="submit">カートに入れる</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
>>>>>>> Stashed changes
@endsection
