<!DOCTYPE html>
<html lang="ja" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - すごい文房具サイト</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    @vite(['resources/css/app.css'])
</head>

<body>
    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ url('/') }}" class="site-logo">
                <img src="{{ asset('images/ec-logo.png') }}" alt="すごい文房具サイト">
            </a>
            <nav class="site-nav">
                <a href="{{ url('/cart') }}">カート</a>
                @auth
                    <a href="{{ url('/mypage') }}">マイページ</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">ログアウト</button>
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
        <p>© HAL東京</p>
    </footer>
</body>

</html>
