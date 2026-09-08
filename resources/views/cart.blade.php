@extends('layouts.base')

@section('title', 'カート')

@section('content')
    <div class="content-width narrow-page">
        <div class="page-heading">
            <div>
                <p class="eyebrow">SHOPPING CART</p>
                <h1>カート</h1>
            </div>
            <a class="text-link" href="{{ route('home') }}">買い物を続ける</a>
        </div>

        @if (session('message'))
            <div class="notice" role="status">{{ session('message') }}</div>
        @endif

        @if ($errors->any())
            <div class="form-errors" role="alert">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        @forelse ($items as $item)
            <article class="cart-item">
                <img src="{{ $item['product']->imageUrl() }}" alt="{{ $item['product']->name }}">
                <div class="cart-item__summary">
                    <p class="product-category">{{ $item['product']->category?->name ?? '文房具' }}</p>
                    <h2><a href="{{ route('products.show', $item['product']) }}">{{ $item['product']->name }}</a></h2>
                    <p class="price">&yen;{{ number_format($item['product']->price) }}</p>
                    @include('products.stock', ['product' => $item['product']])
                </div>
                <div class="cart-item__actions">
                    <form action="{{ route('cart.update', $item['product']) }}" method="POST" class="quantity-form">
                        @csrf
                        @method('PATCH')
                        <label for="quantity-{{ $item['product']->id }}">数量</label>
                        <input id="quantity-{{ $item['product']->id }}" type="number" name="quantity" min="1" max="10" value="{{ $item['quantity'] }}">
                        <button class="button button--outline" type="submit">更新</button>
                    </form>
                    <form action="{{ route('cart.destroy', $item['product']) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="text-button" type="submit">削除</button>
                    </form>
                </div>
                <p class="cart-item__subtotal">&yen;{{ number_format($item['product']->price * $item['quantity']) }}</p>
            </article>
        @empty
            <section class="empty-state">
                <h2>カートは空です。</h2>
                <p>気になる文房具を見つけて、カートに追加してください。</p>
                <a class="button button--primary" href="{{ route('home') }}">商品を見る</a>
            </section>
        @endforelse

        @if ($items->isNotEmpty())
            <section class="cart-summary">
                <div>
                    <p>合計</p>
                    <p class="price price--large">&yen;{{ number_format($totalPrice) }}</p>
                </div>
                <div class="cart-summary__actions">
                    @auth
                        <form action="{{ route('orders.store') }}" method="POST">
                            @csrf
                            <button class="button button--primary" type="submit">購入を確定する</button>
                        </form>
                    @else
                        <a class="button button--primary" href="{{ route('login') }}">ログインして購入する</a>
                    @endauth
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="text-button" type="submit">カートを空にする</button>
                    </form>
                </div>
            </section>
        @endif
    </div>
@endsection
