@extends('layouts.base')

@section('title', $category->name)

@section('content')
    <a class="back-link" href="{{ route('home') }}">すべての商品を見る</a>
    <div class="catalog-heading"><div><p class="eyebrow">COLLECTION</p><h1>{{ $category->name }}</h1></div><span class="item-count">{{ $category->products->count() }}点の商品</span></div>
    <section class="catalog-list" aria-label="商品">
        @forelse ($category->products as $product)
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
            <p>このカテゴリーには商品がありません。</p>
        @endforelse
    </section>
@endsection
