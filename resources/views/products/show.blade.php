@extends('layouts.base')

@section('content')
    <h1>商品詳細</h1>
    <h2>{{ $product->name }}</h2>
    <p>{{ $product->price }}円</p>
    <!-- Debug: Full product data -->
    <!-- <pre>{{ print_r($product->toArray(), true) }}</pre> -->
    <form action="{{ route('cart.store') }}" method="post">
        @csrf
        <input type="hidden" name="productId" value="{{ $product->id }}">
        <input type="number" name="quantity" value="1" min="1">
        <input type="submit" value="カートに入れる">

    </form>
    <!-- <p>{{ $product->description }}</p> -->
    <!-- <p>在庫：{{ $product->stock }}個</p> -->
@endsection