@extends('layouts.base')
@section('title', 'カート')
@section('content')
    <p class="eyebrow">YOUR SELECTION</p><h1>ショッピングカート</h1>
    @forelse ($items as $item)
        <article class="cart-item"><img src="{{ $item['product']->imageUrl() }}" alt="{{ $item['product']->name }}"><div><h3><a href="{{ route('products.show', $item['product']) }}">{{ $item['product']->name }}</a></h3><p>¥{{ number_format($item['product']->price) }} / 個</p></div>
            <form class="quantity-form" action="{{ route('cart.store') }}" method="POST">@csrf<input type="hidden" name="productId" value="{{ $item['product']->id }}"><label for="quantity-{{ $item['product']->id }}">数量</label><input id="quantity-{{ $item['product']->id }}" name="quantity" type="number" min="1" max="{{ min(10, $item['product']->stock) }}" value="{{ $item['quantity'] }}" required><button type="submit">更新</button></form>
            <strong>¥{{ number_format($item['product']->price * $item['quantity']) }}</strong>
            <form action="{{ route('cart.destroy', $item['product']->id) }}" method="POST">@csrf @method('DELETE')<button class="text-button" type="submit">削除</button></form>
        </article>
    @empty<div class="empty-state"><h2>カートは空です</h2><p>毎日を彩る、お気に入りの文房具を探してみませんか。</p><a class="button" href="{{ route('home') }}">商品を見に行く →</a></div>@endforelse
    @if ($items)<div class="cart-summary"><span>合計金額</span><strong class="price">¥{{ number_format($totalPrice) }}</strong>
        @auth<form action="{{ route('orders.store') }}" method="POST">@csrf<button type="submit">注文を確定する →</button></form>@else<a class="button" href="{{ route('login') }}">ログインして購入する</a>@endauth
        <form action="{{ route('cart.clear') }}" method="POST">@csrf @method('DELETE')<button class="text-button" type="submit">カートを空にする</button></form>
    </div>@endif
@endsection
