@extends('layouts.base')

@section('title', $product->name)

@section('content')
    <h1>{{ $product->name }}</h1>
    @if ($product->category)
        <p>カテゴリー: <a href="{{ route('categories.show', $product->category) }}">{{ $product->category->name }}</a></p>
    @endif
    <img class="product-photo" src="{{ $product->imageUrl() }}" width="400" height="320" alt="{{ $product->name }}">
    <p>{{ number_format($product->price) }}円</p>
    <p>{{ $product->description }}</p>
    @include('products.stock', ['product' => $product])
    @if ($errors->any())
        <div class="form-errors" role="alert">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif
    <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <label for="quantity">個数:</label>
        <input id="quantity" type="number" name="quantity" min="1" max="{{ max(1, min(10, $product->stock)) }}" value="{{ old('quantity', 1) }}" required @disabled($product->stock <= 0)>
        <button type="submit" @disabled($product->stock <= 0)>カートに入れる</button>
    </form>
    <a href="{{ route('home') }}">商品一覧に戻る</a>
@endsection
