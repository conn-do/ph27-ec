<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="余白は、書く時間を心地よくする文房具のオンラインショップです。">
        <title>
            @yield('title', '暮らしに、書く余白を。') — 余白 YOHAKU
        </title>
        @vite(['resources/css/shop.css', 'resources/js/shop.js'])
    </head>
    <body>
        <a class="skip-link" href="#main">
            本文へ移動
        </a>
        <div class="announcement">
            書くことから、日々を豊かに。
            <span>
                ¥{{ number_format(config('shop.free_shipping_threshold')) }}以上のお買い物で送料無料
            </span>
        </div>
        <header class="site-header wrap">
            <a class="brand" href="{{ route('home') }}" aria-label="余白 YOHAKU ホーム">
                <strong>
                    余白
                    <span>
                        。
                    </span>
                </strong>
                <small>
                    YOHAKU / STATIONERY
                </small>
            </a>
            <nav aria-label="メインナビゲーション">
                <a href="{{ route('home') }}#collection">
                    商品を探す
                </a>
                <a href="{{ route('products.ranking') }}">
                    ランキング
                </a>
                @auth
                    <a href="{{ route('favorites.index') }}">
                        お気に入り
                    </a>
                @endauth
                <a class="desktop-link" href="{{ route('home') }}#journal">
                    お知らせ
                </a>
            </nav>
            <div class="header-actions">
                @auth
                    <a href="{{ route('mypage') }}">
                        マイページ
                    </a>
                @else
                    <a href="{{ route('login') }}">
                        ログイン
                    </a>
                @endauth
                <a class="cart-link" href="{{ route('cart.index') }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path d="M5 7h14l1 14H4L5 7Z M8 8V6a4 4 0 0 1 8 0v2"/>
                    </svg>
                    <span>
                        カート
                    </span>
                    <b>
                        {{ array_sum(session('cart', [])) }}
                    </b>
                </a>
            </div>
        </header>
        <main id="main" class="wrap" tabindex="-1">
            @if (session('message'))
                <div class="notice" role="status">
                    {{ session('message') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="notice error-notice" role="alert">
                    <strong>
                        入力内容をご確認ください。
                    </strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
        <footer class="site-footer">
            <div class="wrap footer-inner">
                <div>
                    <a class="brand" href="{{ route('home') }}">
                        <strong>
                            余白。
                        </strong>
                        <small>
                            YOHAKU / STATIONERY
                        </small>
                    </a>
                    <p>
                        お気に入りの道具と、心地よい毎日を。
                    </p>
                </div>
                <div>
                    <a href="{{ route('home') }}#collection">
                        商品一覧
                    </a>
                    <a href="{{ route('orders.index') }}">
                        注文履歴
                    </a>
                    <a href="{{ route('products.ranking') }}">
                        ランキング
                    </a>
                    @auth
                        <a href="{{ route('favorites.index') }}">
                            お気に入り
                        </a>
                    @endauth
                    <a href="{{ route('home') }}#journal">
                        お知らせ
                    </a>
                </div>
            </div>
            <div class="wrap footer-bottom">
                <span>
                    © {{ date('Y') }} YOHAKU / PH27
                </span>
                <span>
                    学習用デモショップ · 実際の決済・配送は行われません
                </span>
            </div>
        </footer>
    </body>
</html>
