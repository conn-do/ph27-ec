@extends('layouts.base')

@section('content')
    <h2>商品一覧</h2>

    <ul>
        @foreach ($products as $product)
            <li>
                {{ $product->id }}
                <a href="{{ route('products.show', ['id' => $product->id]) }}">
                    {{ $product->name }}
                </a>
                <div>
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" style="max-width: 150px; max-height: 150px;">
                </div>
                {{ $product->price }} 円
            </li>
        @endforeach
    </ul>
    <h2>
        セール中の商品
    </h2>
    <ul>
        @foreach ($saleProducts as $product)
            <li>
                {{ $product->name }}
                {{ $product->price }} 円
            </li>
        @endforeach
    </ul>
@endsection
