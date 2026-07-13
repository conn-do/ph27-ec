@extends('layouts.base')

@section('title', $product->name)

@section('content')
    <h2>{{ $product->name }}</h2>
    <img src="{{ $product->imageUrl() }}" width="400">
    <p>{{ $product->price }}円</p>
    <p>{{ $product->description }}</p>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <article class="error">
                {{ $error }}
            </article>
        @endforeach
    @endif
    <form action="/cart" method="POST">
        個数:<input type="number" name="quantity" class="@error('quantity') error @enderror" value="{{ old('quantity', 1) }}">
        <input type="hidden" name="productId" value="{{ $product->id }}">
        <input type="submit" value="カートに入れる">
    </form>
@endsection
