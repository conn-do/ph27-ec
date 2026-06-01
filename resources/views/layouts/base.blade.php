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
            @yield('title', '') すごい文房具ECサイト
        </h1>
        @auth
            <a href="{{ route('my-page') }}">マイページ</a>
        @endauth
        @guest
            <a href="{{ route('login') }}">ログイン</a>
        @endguest
    </header>
    <main>
        @yield('content')
    </main>
</body>

</html>
