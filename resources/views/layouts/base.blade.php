<html>

<head>
    <meta charset="UTF-8">
    <title>@yield('title') - すごい文房具サイト</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header class="flex items-center justify-between px-16 py-7 border-b border-navy-200">
        <a href="/" class="text-lg font-medium tracking-wide">
            <img src="{{ asset('images/ec-logo.png') }}" width="100">
        </a>
        <div class="flex items-center gap-8 text-sm font-normal">
            <a href="/cart" class="text-navy-950 hover:text-navy-500">カートを見る</a>
            @auth
                <a href="/mypage" class="text-navy-950 hover:text-navy-500">マイページ</a>
                <a href="/favorites" class="text-navy-950 hover:text-navy-500">お気に入り</a>
                <form method="POST" action="{{ route('logout') }}">
                    <button type="submit" class="border border-navy-200 rounded-full px-4 py-1.5 text-xs text-navy-950 hover:bg-navy-100 transition cursor-pointer">ログアウト</button>
                </form>
            @endauth
            @guest
                <a href="{{ route('login') }}" class="border border-navy-200 rounded-full px-4 py-1.5 text-xs text-navy-950 hover:bg-navy-100 transition">ログイン</a>
            @endguest
        </div>
    </header>
    <main class="max-w-5xl mx-auto px-16">
        @yield('content')
    </main>
    <footer class="text-center text-xs text-navy-500 py-10 mt-20 border-t border-navy-200">
        © HAL東京
    </footer>
</body>

</html>