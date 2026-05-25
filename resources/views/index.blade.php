@extends('layouts.base')

@section('title', 'TOPページ')

@section('content')
    <h2>商品一覧</h2>
    <ul>
        @foreach ($products as $product)
            <li>
                <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
            </li>
        @endforeach
    </ul>
@endsection
