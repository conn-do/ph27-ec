@extends('layouts.base')

@section('title', $product->name)

@section('content')
    @auth
        @php
            $isFavorite = $product
                ->favorites()
                ->where('user_id', auth()->id())
                ->exists();
        @endphp
    @endauth
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
    @auth
        @if ($isFavorite)
            <form action="{{ route('favorites.destroy', $product) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">♥ お気に入りから削除</button>
            </form>
        @else
            <form action="{{ route('favorites.store', $product) }}" method="POST">
                @csrf
                <button type="submit">♡ お気に入りに追加</button>
            </form>
        @endif
    @else
        <p>
            お気に入りを利用するには
            <a href="{{ route('login') }}">ログイン</a>
            してください。
        </p>
    @endauth
    <form action="/cart" method="POST">
        個数:<input type="number" name="quantity" class="@error('quantity') error @enderror"
            value="{{ old('quantity', 1) }}">
        <input type="hidden" name="productId" value="{{ $product->id }}">
        <input type="submit" value="カートに入れる">
    </form>
@endsection
