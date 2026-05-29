@extends('layouts.base')

@section('title', '商品詳細')

@section('content')
    <h2>{{ $product->name }}</h2>
    <p>{{ $product->description }}</p>
    <p>{{ $product->price }}円</p>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <article>{{ $error }}</article>
        @endforeach
    @endif
    <form action="{{ route('cart.store') }}" method="post">
        @csrf
        <input type="hidden" name="productId" value="{{ $product->id }}">
        <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" max="{{ $product->stock }}"
            @if ($errors->has('quantity')) style="border-color: red;" @endif>
        <button type="submit">カートに追加</button>
    </form>
    <a href="{{ route('home') }}">戻る</a>
@endsection
