@extends('layouts.base')

@section('title', $product->name)

@section('content')
    <div class="container">
        <div class="product-detail-container">

            <div class="product-detail-image">
                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">
            </div>

            <div class="product-detail-info">
                <h2 class="product-detail-title">{{ $product->name }}</h2>

                <div class="product-detail-category">
                    カテゴリー:
                    <a href="/categories/{{ $product->category->slug }}">
                        {{ $product->category->name }}
                    </a>
                </div>

                <p class="product-detail-price">¥{{ number_format($product->price) }}</p>

                <p class="product-detail-description">{{ $product->description }}</p>

                <div class="product-detail-stock">
                    @if ($product->stock <= 0)
                        <p class="out-of-stock">売り切れ</p>
                    @elseif ($product->stock <= 5)
                        <p class="low-stock">残りわずか</p>
                    @else
                        <p class="in-stock">在庫あり</p>
                    @endif
                </div>

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <article class="error">
                            {{ $error }}
                        </article>
                    @endforeach
                @endif

                {{-- カートボタンとお気に入りボタンを横並びにするエリア --}}
                <div class="product-action-area">
                    <form action="/cart" method="POST" class="cart-form">
                        @csrf
                        <label>
                            個数: <input type="number" name="quantity" class="@error('quantity') error @enderror"
                                value="{{ old('quantity', 1) }}" min="1">
                        </label>
                        <input type="hidden" name="productId" value="{{ $product->id }}">
                        <button type="submit" class="buy-btn">カートに入れる</button>
                    </form>

                    <div class="favorite-action">
                        @auth
                            @if ($product->isFavoritedBy(auth()->user()))
                                {{-- お気に入り解除フォーム --}}
                                <form action="{{ route('favorites.destroy', $product) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="favorite-icon-btn active" title="お気に入り解除">♥</button>
                                </form>
                            @else
                                {{-- お気に入り登録フォーム --}}
                                <form action="{{ route('favorites.store', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="favorite-icon-btn" title="お気に入り登録">♡</button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="favorite-icon-btn" title="ログインしてお気に入りに追加">♡</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
