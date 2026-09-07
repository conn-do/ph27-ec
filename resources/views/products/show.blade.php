@extends('layouts.base')
@section('title', $product->name)
@section('content')
    <a class="back-link" href="{{ route('home') }}">← 商品一覧に戻る</a>
    <div class="product-detail"><div class="detail-image"><img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}"></div><div>
        <p class="eyebrow">STATIONERY COLLECTION</p><h1>{{ $product->name }}</h1>
        @if ($product->category)<a class="chip" href="{{ route('categories.show', $product->category) }}">{{ $product->category->name }}</a>@endif
        <p class="price">¥{{ number_format($product->price) }}</p><p>{{ $product->description }}</p>
        <p class="availability">{{ $product->stock > 0 ? '在庫あり · 残り'.$product->stock.'個' : '売り切れ' }}</p>
        @if ($product->stock > 0)<form action="{{ route('cart.store') }}" method="POST">@csrf
            <label for="quantity">数量（同じ商品の数量は上書きされます）</label>
            <input id="quantity" type="number" name="quantity" min="1" max="{{ min(10, $product->stock) }}" value="{{ old('quantity', 1) }}" required>
            <input type="hidden" name="productId" value="{{ $product->id }}"><button type="submit">カートに入れる →</button>
        </form>@else<button disabled>ただいま品切れです</button>@endif
    </div></div>
@endsection
