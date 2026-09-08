@extends('layouts.base')

@section('title', 'お気に入り一覧')

@section('content')
    <h1 class="page-title">お気に入り一覧</h1>

    <div class="product-grid">
        @forelse ($favorites as $favorite)
            <a class="product-card" href="/products/{{ $favorite->product->id }}">
                <img src="{{ $favorite->product->imageUrl() }}" alt="{{ $favorite->product->name }}">
                <span class="product-card-body">
                    <span class="product-card-name">{{ $favorite->product->name }}</span>
                    <span class="product-card-price">{{ number_format($favorite->product->price) }}円</span>
                </span>
            </a>
        @empty
            <p class="empty-note">お気に入り登録した商品はまだありません。</p>
        @endforelse
    </div>
@endsection
