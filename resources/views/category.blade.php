@extends('layouts.base')

@section('title', $category->name)

@section('content')
    <section class="page-heading">
        <p class="eyebrow">CATEGORY</p>
        <h1>{{ $category->name }}</h1>
    </section>

    <div class="product-grid">
        @forelse ($category->products as $product)
            <article class="product-card">
                <a href="/products/{{ $product->id }}">
                    <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">
                    <span class="product-name">{{ $product->name }}</span>
                    <span class="product-price">{{ number_format($product->price) }}円</span>
                </a>
            </article>
        @empty
            <p>このカテゴリの商品はまだありません。</p>
        @endforelse
    </div>
@endsection
