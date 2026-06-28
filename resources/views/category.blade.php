@extends('layouts.base')

@section('title', $category->name)

@section('content')
    <h2>カテゴリ：{{ $category->name }}</h2>
    @if (request('keyword'))
        <a href="{{ route('products.index') }}">検索結果をクリア</a>
    @endif
    <ul>
        @foreach ($products as $product)
            <li>
                <a href="{{ route('products.show', $product) }}">
                    {{ $product->name }}
                    <img src="{{ asset($product->imageUrl()) }}" alt="{{ $product->name }}" width="200">
                </a>
            </li>
        @endforeach
    </ul>
@endsection
