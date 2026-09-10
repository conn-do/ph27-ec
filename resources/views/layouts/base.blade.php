<html>

<head>
    <meta charset="UTF-8">
    <title>@yield('title') - すごい文房具サイト</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="container">

    <header>

        {{-- ロゴ --}}
        <a href="/">
            <img
                src="{{ asset('images/ec-logo.png') }}"
                width="100"
            >
        </a>

        {{-- カート --}}
        <a href="/cart">
            カートを見る
        </a>

        @auth

            {{-- マイページ --}}
            <a href="/mypage">
                マイページ
            </a>

            {{-- お気に入り --}}
            <a href="{{ route('favorites.index') }}">
                ♡ お気に入り
            </a>

            {{-- ログアウト --}}
            <form
                method="POST"
                action="{{ route('logout') }}"
            >
                @csrf

                <button type="submit">
                    ログアウト
                </button>
            </form>

        @endauth

        @guest

            {{-- ログイン --}}
            <a href="{{ route('login') }}">
                ログイン
            </a>

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