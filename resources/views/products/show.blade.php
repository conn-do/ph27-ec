@extends('layouts.base')

@section('title', $product->name)

@section('content')
    <div class="product-detail">
        <div class="product-detail-media">
            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">
        </div>
        <div class="product-detail-info">
            <h2>{{ $product->name }}</h2>
            <div class="product-meta">
                カテゴリー:
                <a href="/categories/{{ $product->category->slug }}">
                    {{ $product->category->name }}
                </a>
            </div>
            <p class="product-price">{{ number_format($product->price) }}円</p>
            <p>{{ $product->description }}</p>
            @if ($product->stock <= 0)
                <p class="stock-note">売り切れ</p>
            @elseif ($product->stock <= 5)
                <p class="stock-note">残りわずか</p>
            @else
                <p class="stock-note">在庫あり</p>
            @endif

            @auth
                @php
                    $isFavorited = $product->favorites->where('user_id', auth()->id())->count() > 0;
                @endphp
                <div class="inline-actions">
                    @if ($isFavorited)
                        <form action="/favorites/{{ $product->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="submit" value="お気に入り解除">
                        </form>
                    @else
                        <form action="/favorites/{{ $product->id }}" method="POST">
                            @csrf
                            <input type="submit" value="お気に入りに追加">
                        </form>
                    @endif
                </div>
            @endauth

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <article class="error">
                        {{ $error }}
                    </article>
                @endforeach
            @endif

            <form class="cart-form" action="/cart" method="POST">
                @csrf
                個数:
                <input type="number" name="quantity" class="@error('quantity') error @enderror"
                    value="{{ old('quantity', 1) }}">
                <input type="hidden" name="productId" value="{{ $product->id }}">
                <input type="submit" value="カートに入れる">
            </form>
        </div>
    </div>

    <section class="reviews">
        <h3>レビュー</h3>
        @auth
            <form class="review-form" action="/products/{{ $product->id }}/reviews" method="POST">
                @csrf
                <div>
                    評価:
                    <select name="rating">
                        <option value="5">★★★★★</option>
                        <option value="4">★★★★</option>
                        <option value="3">★★★</option>
                        <option value="2">★★</option>
                        <option value="1">★</option>
                    </select>
                </div>
                <div>
                    <textarea name="comment" placeholder="コメント(任意)"></textarea>
                </div>
                <input type="submit" value="レビューを投稿">
            </form>
        @endauth

        @forelse ($product->reviews as $review)
            <article class="review-item">
                <p>{{ $review->user->name }} さん - 評価: {{ $review->rating }}</p>
                <p>{{ $review->comment }}</p>
                <p><small>{{ $review->created_at->format('Y/m/d') }}</small></p>
            </article>
        @empty
            <p class="empty-note">まだレビューがありません。</p>
        @endforelse
    </section>
@endsection
