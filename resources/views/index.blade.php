@extends('layouts.base')

@section('title', 'TOPページ')

@section('content')
    <h2>商品一覧</h2>
    <form action="{{ route('search') }}" method="get">
        <input type="text" name="keyword" placeholder="商品検索" value="{{ request('keyword') }}">
        <button type="submit">検索</button>
    </form>
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
