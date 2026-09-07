<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
<<<<<<< Updated upstream
    <title>@yield('title') - すごい文房具サイト</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
=======
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Note & Co.</title>
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
>>>>>>> Stashed changes
</head>
<body class="storefront">
    <header class="site-header">
        <div class="site-header__inner">
            <a class="brand" href="{{ route('home') }}" aria-label="Note & Co. ホーム">
                <img src="{{ asset('images/ec-logo.png') }}" alt="Note & Co.">
                <span>NOTE & CO.</span>
            </a>
            <nav class="site-nav" aria-label="メインメニュー">
                <a href="{{ route('home') }}">商品を探す</a>
                <a href="{{ route('cart.index') }}">カート <span class="cart-count">{{ collect(session('cart', []))->sum() }}</span></a>
                @auth
                    <a href="{{ route('orders.index') }}">注文履歴</a>
                    <a href="{{ route('dashboard') }}">マイページ</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="nav-button" type="submit">ログアウト</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">ログイン</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <div>NOTE & CO. | Everyday stationery for clear ideas.</div>
        <div>&copy; HAL Tokyo</div>
    </footer>
</body>
</html>
