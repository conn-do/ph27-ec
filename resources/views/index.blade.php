@extends('layouts.base')


@section('title', '商品一覧')

@section('content')
    <h2>商品一覧</h2>
    @foreach ($products as $product)
        <ul>
            <li>
                <a href="/products/{{ $product->id }}">
                    {{ $product['name'] }}
                    <img src="{{ asset($product->image) }}" width="200">
                </a>
            </li>
        </ul>
    @endforeach
@endsection
