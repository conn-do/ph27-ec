@extends('layouts.base')

@section('title', 'お気に入り')

@section('content')
    <h2>お気に入り</h2>

    @if ($favorites->isEmpty())
        <p>お気に入りの商品がありません。</p>
    @else
        @foreach ($favorites as $favorite)
            <article>
                <a href="/products/{{ $favorite->product->id }}">
                    <img src="{{ $favorite->product->imageUrl() }}" width="200">

                    <h3>{{ $favorite->product->name }}</h3>

                    <p>
                        {{ number_format($favorite->product->price) }}円
                    </p>
                </a>

                <form action="/cart" method="POST">
                    @csrf

                    <input
                        type="hidden"
                        name="productId"
                        value="{{ $favorite->product->id }}"
                    >

                    <input
                        type="number"
                        name="quantity"
                        value="1"
                        min="1"
                        max="10"
                    >

                    <input
                        type="submit"
                        value="カートに入れる"
                    >
                </form>
            </article>
        @endforeach
    @endif
@endsection