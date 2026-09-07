@extends('layouts.base')
@section('title', $category->name)
@section('content')
    <a class="back-link" href="{{ route('home') }}">← すべての商品</a>
    <p class="eyebrow">COLLECTION</p><h1>{{ $category->name }}</h1>
    <div class="product-grid">@forelse ($category->products as $product)<x-product-card :product="$product" />@empty<p class="empty-state">このカテゴリの商品は準備中です。</p>@endforelse</div>
@endsection
