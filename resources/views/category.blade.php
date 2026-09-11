@extends('layouts.base')

@section('title', $category->name)

@section('content')

    <section class="category-page">
        <div class="product-page-heading">
            <h2>{{ $category->name }}</h2>
        </div>

        <div class="product-list">
            @foreach ($category->products as $product)
                <article class="product-item">
                    <a href="/products/{{ $product->id }}">
                        <div class="product-image">
                            <img
                                src="{{ $product->imageUrl() }}"
                                alt="{{ $product->name }}"
                            >
                        </div>

                        <h3>{{ $product->name }}</h3>

                        <p>
                            ¥{{ number_format($product->price) }}
                        </p>
                    </a>
                </article>
            @endforeach
        </div>
    </section>

     <div class="ranking-space">
        <div class="ranking-space-column"></div>
        <div class="ranking-space-column"></div>
        <div class="ranking-space-column"></div>
    </div>

@endsection