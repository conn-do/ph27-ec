@extends('layouts.base')

@section('title', 'お気に入り')

@section('content')

    <h2>お気に入り</h2>

    @if ($favorites->isEmpty())

        <p>お気に入りの商品はありません。</p>

    @else

        <div class="product-list">

            @foreach ($favorites as $favorite)

                <div class="product-card">

                    <a href="/products/{{ $favorite->product->id }}">

                        <img
                            src="{{ $favorite->product->imageUrl() }}"
                            alt="{{ $favorite->product->name }}"
                        >

                        <h3>
                            {{ $favorite->product->name }}
                        </h3>

                    </a>

                    <form
                        action="/products/{{ $favorite->product->id }}/favorite"
                        method="POST"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit">
                            ♥ お気に入りから削除
                        </button>

                    </form>

                </div>

            @endforeach

        </div>

    @endif

@endsection