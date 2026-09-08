<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - すごい文房具サイト</title>
    @vite('resources/css/app.css')
</head>
<body @class(['storefront', 'auth-page' => request()->routeIs('login', 'register')])>
    <div class="store-container">
        <header class="site-header">
            <a href="{{ route('home') }}" class="brand"><img src="{{ asset('images/ec-logo.png') }}" width="150" height="82" alt="すごい文房具サイト"><span>STATIONERY &amp; EVERYDAY</span></a>
            <nav class="site-nav" aria-label="メインメニュー">
                <a href="{{ route('home') }}" @if(request()->routeIs('home', 'categories.show', 'products.show')) aria-current="page" @endif>商品一覧</a>
                <a href="{{ route('cart.index') }}" class="cart-link" @if(request()->routeIs('cart.index')) aria-current="page" @endif>カートを見る <span class="cart-count">{{ array_sum(session('cart', [])) }}</span></a>
                @auth
                    <a href="{{ route('dashboard') }}">マイページ</a>
                    <a href="{{ route('orders.index') }}">注文履歴</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-button" type="submit">ログアウト</button>
                    </form>
                @else
                    @unless(request()->routeIs('login', 'register'))
                        <a href="{{ route('login') }}">ログイン</a>
                        <a href="{{ route('register') }}">会員登録</a>
                    @endunless
                @endauth
            </nav>
        </header>
        <main id="main-content">@yield('content')</main>
        <footer class="site-footer"><div><strong>すごい文房具</strong><span>STATIONERY &amp; EVERYDAY</span></div><small>&copy; HAL東京</small></footer>
    </div>
</body>
</html>
