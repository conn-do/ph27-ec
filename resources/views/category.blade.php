@extends('layouts.base')

@section('title', $category->name)

@section('content')
    <h1 class="page-title">{{ $category->name }}</h1>
    <div class="product-grid">
        @forelse ($category->products as $product)
            <a class="product-card" href="/products/{{ $product->id }}">
                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">
                <span class="product-card-body">
                    <span class="product-card-name">{{ $product->name }}</span>
                    <span class="product-card-price">{{ number_format($product->price) }}円</span>
                </span>
            </a>
        @empty
            <p class="empty-note">このカテゴリの商品はまだありません。</p>
        @endforelse
    </div>
@endsection
