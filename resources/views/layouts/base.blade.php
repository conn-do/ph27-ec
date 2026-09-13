<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - すごい文房具サイト</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
</head>

<body>
    <div class="site-shell">
        <header class="site-header">
            <a class="site-logo" href="/" aria-label="商品一覧へ戻る">
                <img src="{{ asset('images/ec-logo.png') }}" width="100" alt="すごい文房具サイト">
            </a>
            <nav class="site-nav" aria-label="メインナビゲーション">
                <a href="/">商品一覧</a>
                <a href="/cart">カートを見る</a>
                @auth
                    <a href="/mypage">マイページ</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">ログアウト</button>
                    </form>
                @endauth
                @guest
                    <a href="{{ route('login') }}">ログイン</a>
                @endguest
            </nav>
        </header>
        <main>
            @yield('content')
        </main>
        <footer class="site-footer">
            © HAL東京
        </footer>
    </div>
</body>

</html>
