<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'すごい文房具ECサイト')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <!-- ヘッダー -->
    <header class="site-header">
        <a href="/" class="site-logo">すごい文房具</a>
        
        <nav class="site-nav">
            <a href="/cart">カートを見る</a>
            @auth
                <a href="/mypage">マイページ</a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline; margin: 0;">
                    @csrf
                    <button type="submit" class="nav-logout">ログアウト</button>
                </form>
            @else
                <a href="{{ route('login') }}">ログイン</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="nav-register">会員登録</a>
                @endif
            @endauth
        </nav>
    </header>

    <!-- メインコンテンツ -->
    <main class="site-main">
        @yield('content')
    </main>

</body>
</html>