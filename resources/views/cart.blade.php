@extends('layouts.base')

@section('title', 'カート')

@section('content')
    <section class="cart-page" aria-labelledby="cart-heading">
        <h1 id="cart-heading">ショッピングカート</h1>

        @if (session('message'))
            <p class="store-message">{{ session('message') }}</p>
        @endif
        @if ($items->isEmpty())
            <div class="empty-state">
                @error('cart')
                    <p>{{ $message }}</p>
                @else
                    <p>カートに商品がありません。</p>
                @enderror
                <a href="{{ route('home') }}#catalog-heading">商品一覧を見る</a>
            </div>
        @else
            @error('cart')
                <p class="form-error">{{ $message }}</p>
            @enderror

            <div class="cart-layout">
                <div class="cart-items">
                    @foreach ($items as $item)
                        <article class="cart-item">
                            <a href="{{ route('products.show', $item['product']) }}">
                                <img src="{{ $item['product']->imageUrl() }}" alt="{{ $item['product']->name }}">
                            </a>
                            <div class="cart-item-info">
                                <h2><a
                                        href="{{ route('products.show', $item['product']) }}">{{ $item['product']->name }}</a>
                                </h2>
                                <p>¥{{ number_format($item['product']->price) }}</p>
                            </div>
                            <form class="cart-quantity" action="{{ route('cart.update', $item['product']) }}"
                                method="POST">
                                @csrf
                                @method('PATCH')
                                <label for="quantity-{{ $item['product']->id }}">数量</label>
                                <input id="quantity-{{ $item['product']->id }}" type="number" name="quantity"
                                    value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock }}">
                                <button type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100"
                                        viewBox="0 0 32 32">
                                        <path
                                            d="M 16 4 C 10.886719 4 6.617188 7.160156 4.875 11.625 L 6.71875 12.375 C 8.175781 8.640625 11.710938 6 16 6 C 19.242188 6 22.132813 7.589844 23.9375 10 L 20 10 L 20 12 L 27 12 L 27 5 L 25 5 L 25 8.09375 C 22.808594 5.582031 19.570313 4 16 4 Z M 25.28125 19.625 C 23.824219 23.359375 20.289063 26 16 26 C 12.722656 26 9.84375 24.386719 8.03125 22 L 12 22 L 12 20 L 5 20 L 5 27 L 7 27 L 7 23.90625 C 9.1875 26.386719 12.394531 28 16 28 C 21.113281 28 25.382813 24.839844 27.125 20.375 Z">
                                        </path>
                                    </svg>
                                    <span>更新</span>
                                </button>
                            </form>
                            <form class="cart-remove" action="{{ route('cart.destroy', $item['product']) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="{{ $item['product']->name }}を削除">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M4 7h16" />
                                        <path d="M10 11v6M14 11v6" />
                                        <path d="M6 7l1 14h10l1-14M9 7V4h6v3" />
                                    </svg>
                                </button>
                            </form>
                        </article>
                    @endforeach
                </div>

                <aside class="cart-summary">
                    <h2>ご注文内容</h2>
                    <div><span>合計</span><strong>¥{{ number_format($totalPrice) }}</strong></div>
                    @auth
                        <a class="cart-checkout-link" href="{{ route('checkout.create') }}">購入手続きへ</a>
                    @else
                        <a class="cart-checkout-link" href="{{ route('login') }}">ログインして購入手続きへ</a>
                    @endauth
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="cart-clear" type="submit">カートを空にする</button>
                    </form>
                </aside>
            </div>
        @endif
    </section>
@endsection
