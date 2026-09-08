@extends('layouts.base')

@section('title', '検索結果')

@section('content')

    <div class="container">

        <h1 class="section-title">
            検索結果
        </h1>

        @if ($products->isEmpty())

            <div class="empty-box">
                該当する商品がありません。
            </div>
        @else
            <div class="product-grid">

                @foreach ($products as $product)
                    <div class="product-card">

                        <a href="{{ route('products.show', $product->id) }}">

                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            @endif

                            <h3 class="product-name">
                                {{ $product->name }}
                            </h3>

                            <p class="product-price">
                                ¥{{ number_format($product->price) }}
                            </p>

                        </a>

                    </div>
                @endforeach

            </div>

        @endif

    </div>

@endsection
