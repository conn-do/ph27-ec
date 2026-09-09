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

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <article class="error">
                {{ $error }}
            </article>
        @endforeach
    @endif

    @auth
        @php
            $isFavorited = auth()->user()->favorites()->where('product_id', $product->id)->exists();
        @endphp

        <form action="/products/{{ $product->id }}/favorite" method="POST">
            @csrf
            <button type="submit">
                {{ $isFavorited ? 'お気に入り解除' : 'お気に入りに追加' }}
            </button>
        </form>
    @else
        <p>お気に入り機能を使うには<a href="{{ route('login') }}">ログイン</a>してください。</p>
    @endauth

    <form action="/cart" method="POST">
        個数:<input type="number" name="quantity" class="@error('quantity') error @enderror" value="{{ old('quantity', 1) }}">
        <input type="hidden" name="productId" value="{{ $product->id }}">
        <input type="submit" value="カートに入れる">
    </form>

    <section>
        <h3>レビュー</h3>
        @if ($product->reviews->isEmpty())
            <p>まだレビューがありません</p>
        @else
            @foreach ($product->reviews->sortByDesc('created_at') as $review)
                <article>
                    <p>ユーザー: {{ $review->user->name }}</p>
                    <p>評価: {{ $review->rating }} / 5</p>
                    <p>{{ $review->comment }}</p>
                    <p>{{ $review->created_at->format('Y/m/d') }}</p>
                </article>
            @endforeach
        @endif
    </section>

    @auth
        <section>
            <h3>レビューを書く</h3>
            <form action="/products/{{ $product->id }}/reviews" method="POST">
                @csrf
                <label>
                    評価
                    <select name="rating">
                        <option value="">選択してください</option>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }}星</option>
                        @endfor
                    </select>
                </label>
                <label>
                    コメント
                    <textarea name="comment" rows="4">{{ old('comment') }}</textarea>
                </label>
                <button type="submit">レビューを投稿</button>
            </form>
        </section>
    @else
        <p>レビューを投稿するには<a href="{{ route('login') }}">ログイン</a>してください。</p>
    @endauth
@endsection
