<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @yield('title', '') すごい文房具ECサイト
    </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="container">
    <header>
        <h1>
            <a href="{{ route('products.index') }}">
                @yield('title', '') すごい文房具ECサイト
            </a>
        </h1>
        @auth
            <a href="{{ route('home') }}">マイページ</a>
        @endauth
        @guest
            <a href="{{ route('login') }}">ログイン</a>
            <a href="{{ route('register') }}">会員登録</a>
        @endguest
    </header>
    <main>
        @yield('content')
    </main>
</body>

</html>
