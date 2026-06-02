@extends('layouts.base')

@section('title', $product->name)

@section('content')
    <h2>{{ $product->name }}</h2>
    <p>{{ $product->price }}円</p>
    <p>{{ $product->description }}</p>
    <form action="/cart" method="POST">
        個数:<input type="number" name="quantity">
        <input type="hidden" name="productId" value="{{ $product->id }}">
        <input type="submit" value="カートに入れる">
    </form>
@endsection
