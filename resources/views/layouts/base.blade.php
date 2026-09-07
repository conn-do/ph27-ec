<html>

<head>
    <meta charset="UTF-8">
    <title>@yield('title') - すごい文房具サイト</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header class="header">
        <div class="header-inner">

            <a href="/" class="header-logo">
                <img src="{{ asset('images/ec-logo.png') }}" width="120" alt="logo">
            </a>

            <form action="/search" method="GET" class="header-search">
                <input type="text" name="keyword" placeholder="文房具を検索">
                <button type="submit">
                    検索
                </button>
            </form>

            <nav class="header-nav">
                <a href="/cart" class="nav-btn">
                    カート
                </a>

                @guest
                    <a href="{{ route('login') }}" class="nav-btn login-btn">
                        ログイン
                    </a>
                @endguest

                @auth
                    <a href="/mypage" class="nav-btn">
                        マイページ
                    </a>
                @endauth
            </nav>

        </div>
    </header>
    <main>
        @yield('content')
    </main>
    <footer>
        © HAL東京
    </footer>
</body>

</html>
