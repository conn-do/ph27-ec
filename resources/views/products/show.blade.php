@extends('layouts.base')

@section('title', '商品詳細')

@section('content')
    <h2>{{ $product->name }}</h2>
    <img src="{{ asset($product->imageUrl()) }}" alt="{{ $product->name }}" width="500">
    <p>{{ $product->description }}</p>
    <p>{{ $product->price }}円</p>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <article>{{ $error }}</article>
        @endforeach
    @endif
    @if ($product->stock === 0)
        <p>売り切れ！</p>
    @elseif ($product->stock <= 5)
        <p>残り{{ $product->stock }}個！</p>
    @else
        <p>在庫があります</p>
    @endif
    <form action="{{ route('cart.store') }}" method="post">
        @csrf
        <input type="hidden" name="productId" value="{{ $product->id }}">
        <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1"
            @error('quantity') class="error" @enderror>
        <button type="submit">カートに追加</button>
    </form>
    <a href="{{ route('products.index') }}">戻る</a>
@endsection
