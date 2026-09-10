@extends('layouts.base')
@section('title', 'お気に入り')
@section('content')
    <div class="page-heading">
        <p class="eyebrow">YOUR FAVORITES</p>
        <h1>お気に入り</h1>
        <p>あとで見返したい道具を、ここに集めておけます。</p>
    </div>
    <section class="collection favorites-collection" aria-labelledby="favorites-title">
        <div class="section-heading">
            <div>
                <p class="eyebrow">SAVED ITEMS</p>
                <h2 id="favorites-title">保存した文房具</h2>
            </div>
            <span>{{ $products->total() }} items</span>
        </div>
        <div class="product-grid">
            @forelse ($products as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="empty-state">
                    <div class="empty-symbol" aria-hidden="true">♡</div>
                    <h3>まだお気に入りはありません。</h3>
                    <p>気になる商品のハートを押すと、ここからいつでも見返せます。</p>
                    <a class="button button-outline" href="{{ route('home') }}#collection">商品を探す</a>
                </div>
            @endforelse
        </div>
        <x-shop-pagination :paginator="$products" />
    </section>
@endsection
