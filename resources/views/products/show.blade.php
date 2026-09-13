@extends('layouts.base')

@section('title', $product->name)

@section('content')
    <h1>{{ $product->name }}</h1>
    @if ($product->category)
        <p>
            カテゴリー:
            <a href="/categories/{{ $product->category->slug }}">
                {{ $product->category->name }}
            </a>
        </p>
    @endif
    <img src="{{ $product->imageUrl() }}" width="400" alt="{{ $product->name }}">
    <p>{{ $product->price }}円</p>
    <p>{{ $product->description }}</p>
    @if ($product->stock <= 0)
        <p>売り切れ</p>
    @elseif ($product->stock <= 5)
        <p>残りわずか</p>
    @else
        <p>在庫あり</p>
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
            <label>
                個数
                <input type="number" name="quantity" class="@error('quantity') error @enderror" value="{{ old('quantity', 1) }}" min="1" max="{{ min($product->stock, 10) }}">
            </label>
            <input type="hidden" name="productId" value="{{ $product->id }}">
            <input type="submit" value="カートに入れる">
        </form>
    @else
        <p>この商品は現在購入できません。</p>
    @endif
@endsection
