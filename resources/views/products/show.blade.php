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

    @if ($product->stock <= 0)
        <p>売り切れ</p>
    @elseif ($product->stock <= 5)
        <p>残りわずか</p>
    @else
        <p>在庫あり</p>
    @endif


    {{-- お気に入り --}}
    @auth

        @php
            $isFavorite = \App\Models\Favorite::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->exists();
        @endphp

        @if ($isFavorite)

            <form
                action="{{ route('favorites.destroy', $product) }}"
                method="POST"
            >
                @csrf
                @method('DELETE')

                <button type="submit">
                    ♥ お気に入りから削除
                </button>
            </form>

        @else

            <form
                action="{{ route('favorites.store', $product) }}"
                method="POST"
            >
                @csrf

                <button type="submit">
                    ♡ お気に入りに追加
                </button>
            </form>

        @endif

    @else

        <p>
            お気に入り機能を利用するには
            <a href="/login">ログイン</a>
            してください。
        </p>

    @endauth


    {{-- エラーメッセージ --}}
    @if ($errors->any())

        @foreach ($errors->all() as $error)

            <article class="error">
                {{ $error }}
            </article>

        @endforeach

    @endif


    {{-- カート --}}
    <form action="/cart" method="POST">

        @csrf

        個数:

        <input
            type="number"
            name="quantity"
            class="@error('quantity') error @enderror"
            value="{{ old('quantity', 1) }}"
        >

        <input
            type="hidden"
            name="productId"
            value="{{ $product->id }}"
        >

        <input
            type="submit"
            value="カートに入れる"
        >

    </form>

@endsection