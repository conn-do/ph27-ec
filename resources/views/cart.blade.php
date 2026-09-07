@extends('layouts.base')
@section('title', 'ショッピングカート')
@section('content')
    <div class="page-heading">
        <p class="eyebrow">
            YOUR SELECTION
        </p>
        <h1>
            ショッピングカート
        </h1>
        <p>
            毎日を少し豊かにする、お気に入り。
        </p>
    </div>
    @if(empty($items))
        <div class="empty-state">
            <p class="empty-symbol" aria-hidden="true">
                ＋
            </p>
            <h2>
                カートは、まだ空っぽです。
            </h2>
            <p>
                お気に入りの文房具を見つけてみませんか。
            </p>
            <a class="button" href="{{ route('home') }}#collection">
                商品を見に行く ↗
            </a>
        </div>
    @else
        <div class="purchase-layout">
            <section aria-label="カートの商品">
                <div class="cart-list">
                    @foreach($items as $item)
                        <article class="cart-item">
                            <a href="{{ route('products.show', $item['product']) }}">
                                <img src="{{ $item['product']->imageUrl() }}" alt="{{ $item['product']->name }}" width="140" height="140">
                            </a>
                            <div class="cart-item-info">
                                <a href="{{ route('products.show', $item['product']) }}">
                                    <h2>
                                        {{ $item['product']->name }}
                                    </h2>
                                </a>
                                <p>
                                    ¥{{ number_format($item['product']->price) }}
                                    <small>
                                        税込 / 1点
                                    </small>
                                </p>
                                @if($item['quantity'] > $item['product']->stock)
                                    <p class="field-error">
                                        在庫は{{ $item['product']->stock }}点です。数量を変更するか削除してください。
                                    </p>
                                @endif
                                <div class="quantity-actions">
                                    <form action="{{ route('cart.update', $item['product']) }}" method="post" data-submit>
                                        @csrf
                                        @method('PATCH')
                                        <label for="quantity-{{ $item['product']->id }}">
                                            数量
                                        </label>
                                        <input id="quantity-{{ $item['product']->id }}" name="quantity" type="number" min="1" max="{{ min(config('shop.max_quantity'), $item['product']->stock) }}" value="{{ $item['quantity'] }}" required>
                                        <button type="submit" class="text-button">
                                            更新
                                        </button>
                                    </form>
                                    <form action="{{ route('cart.destroy', $item['product']) }}" method="post" data-submit>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-button muted" aria-label="{{ $item['product']->name }}をカートから削除">
                                            削除
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <strong class="line-total">
                                ¥{{ number_format($item['product']->price * $item['quantity']) }}
                            </strong>
                        </article>
                    @endforeach
                </div>
                <div class="cart-bottom">
                    <a href="{{ route('home') }}#collection">
                        ← お買い物を続ける
                    </a>
                    <form method="post" action="{{ route('cart.clear') }}" data-submit>
                        @csrf
                        @method('DELETE')
                        <button class="text-button muted" type="submit">
                            カートを空にする
                        </button>
                    </form>
                </div>
            </section>
            <aside class="order-summary">
                <p class="eyebrow">
                    ORDER SUMMARY
                </p>
                <h2>
                    ご注文内容
                </h2>
                <dl>
                    <div>
                        <dt>
                            商品小計（税込）
                        </dt>
                        <dd>
                            ¥{{ number_format($subtotal) }}
                        </dd>
                    </div>
                    <div>
                        <dt>
                            送料
                        </dt>
                        <dd>
                            {{ $shipping === 0 ? '無料' : '¥'.number_format($shipping) }}
                        </dd>
                    </div>
                    <div class="total">
                        <dt>
                            合計
                        </dt>
                        <dd>
                            ¥{{ number_format($totalPrice) }}
                        </dd>
                    </div>
                </dl>
                @if($shipping > 0)
                    <p class="muted">
                        あと¥{{ number_format(config('shop.free_shipping_threshold') - $subtotal) }}で送料無料
                    </p>
                @endif
                <a class="button full-width" href="{{ route('checkout') }}">
                    ご注文手続きへ →
                </a>
                @guest
                    <p class="muted">
                        次の画面でログインが必要です。
                    </p>
                @endguest
                <p class="demo-note">
                    デモ注文です。実際の決済・配送は発生しません。
                </p>
            </aside>
        </div>
    @endif
@endsection
