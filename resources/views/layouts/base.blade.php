<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title') - すごい文房具サイト</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header class="site-header">
        <div class="header-inner">

            <a href="/" class="site-logo">
                <img src="{{ asset('images/ec-logo.png') }}" alt="すごい文房具サイト">
            </a>

            <nav class="header-nav">
                <a href="/cart">カートを見る</a>

                @auth
                    <a href="/mypage">マイページ</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-button">
                            ログアウト
                        </button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('login') }}">ログイン</a>
                @endguest
            </nav>

        </div>
    </header>

    <main class="site-main">
        @yield('content')
    </main>

    <footer class="site-footer">
        © HAL東京
    </footer>

</body>

</html>
