@extends('layouts.base')

@section('title', 'マイページ')

@section('content')

    <section class="mypage">

        <div class="page-heading">
            <p>MY PAGE</p>
            <h2>マイページ</h2>
        </div>

        <div class="mypage-menu">
            <a href="/orders" class="mypage-menu-link">
                <span>ORDER HISTORY</span>
                <strong>注文履歴</strong>
            </a>
        </div>

        <section class="favorite-section">
            <div class="section-heading">
                <p class="section-label">FAVORITES</p>
                <h3>お気に入り</h3>
            </div>

            @if ($favorites->isEmpty())
                <p class="empty-message">お気に入りの商品はありません。</p>
            @else
                <div class="favorite-grid">
                    @foreach ($favorites as $favorite)
                        <a href="/products/{{ $favorite->product->id }}" class="favorite-card">
                            <div class="favorite-image">
                                <img src="{{ $favorite->product->imageUrl() }}" alt="{{ $favorite->product->name }}">
                            </div>

                            <p>{{ $favorite->product->name }}</p>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>

    </section>

@endsection
