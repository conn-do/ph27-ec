@extends('layouts.base')

@section('title', $product->name)

@section('content')

    <h2>{{ $product->name }}</h2>

    <div>
        カテゴリー:
        <a href="/categories/{{ $product->category->slug }}">
            {{ $product->category->name }}
        </a>
    </div>

    <img src="{{ $product->imageUrl() }}" width="400">

    <p>{{ $product->price }}円</p>

    <p>{{ $product->description }}</p>

    @auth
        <form action="/favorites" method="POST">
            @csrf

            <input type="hidden" name="productId" value="{{ $product->id }}">

            <button type="submit">
                お気に入り
            </button>
        </form>
    @endauth

    @if ($product->stock <= 0)
        <p>売り切れ</p>
    @elseif ($product->stock <= 5)
        <p>残りわずか（在庫：{{ $product->stock }}個）</p>
    @else
        <p>在庫：{{ $product->stock }}個</p>
    @endif

    @if (session('message'))
        <p>{{ session('message') }}</p>
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <article class="error">
                {{ $error }}
            </article>
        @endforeach
    @endif

    @if ($product->stock > 0)
        <form action="/cart" method="POST">
            @csrf

            個数:
            <input
                type="number"
                name="quantity"
                class="@error('quantity') error @enderror"
                value="{{ old('quantity', 1) }}"
                min="1"
                max="10"
            >

            <input
                type="hidden"
                name="productId"
                value="{{ $product->id }}"
            >

            <input type="submit" value="カートに入れる">
        </form>
    @endif

@endsection