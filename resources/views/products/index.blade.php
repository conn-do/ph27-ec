@extends('layouts.base')

@section('content')
    <h2>商品一覧</h2>

    <ul class="grid grid-cols-3 gap-4 mt-8 mb-8">
        @foreach ($products as $product)
            <li class="product-item">
                <a href="{{ route('products.show', ['id' => $product->id]) }}" class="product-link">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}">
                    <p class="product-info">{{ $product->name }}<br>{{ $product->price }} 円</p>
                </a>
            </li>
        @endforeach
    </ul>

    <h2 class="text-red-500">セール中の商品</h2>
    <ul class="grid grid-cols-3 gap-4 mt-8 mb-8 text-red-500">
        @foreach ($saleProducts as $product)
            <li class="product-item">
                <a href="{{ route('products.show', ['id' => $product->id]) }}" class="product-link">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}">
                    <p class="product-info">{{ $product->name }}<br>{{ $product->price }} 円</p>
                </a>
            </li>
        @endforeach
    </ul>
@endsection
