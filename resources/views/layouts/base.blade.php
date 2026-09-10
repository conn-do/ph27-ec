<html>

<head>
    <meta charset="UTF-8">
    <title>@yield('title') - すごい文房具サイト</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="container">
    <header>
        <a href="/">
            <img src="{{ asset('images/ec-logo.png') }}" width="100">
        </a>

        <a href="/cart">
            カートを見る
            @if (count(session('cart', [])) > 0)
                ({{ count(session('cart', [])) }})
            @endif
        </a>

        @auth
            <a href="/favorites">お気に入り</a>

            <a href="/mypage">マイページ</a>
        @endauth

        @guest
            <a href="{{ route('login') }}">ログイン</a>
        @endguest
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        © HAL東京
    </footer>
</body>

</html>