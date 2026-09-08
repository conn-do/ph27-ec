<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') - すごい文房具サイト</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background-color: #13161E; color: #e5e7eb; margin: 0; font-family: sans-serif;">
    <div style="max-width: 900px; margin: 0 auto; padding: 20px;">
        
        <header style="display: flex; justify-content: space-between; align-items: center; padding: 20px 0; border-bottom: 1px solid #2a3245; margin-bottom: 30px;">
            <div>
                <a href="/" style="display: inline-block; text-decoration: none;">
                    <img src="{{ asset('images/ec-logo.png') }}" width="100" style="display: block; max-width: 100%;">
                </a>
            </div>
            <div style="display: flex; align-items: center; gap: 15px; font-size: 13px;">
                <a href="/cart" style="color: #38bdf8; text-decoration: none;">カートを見る</a>
                @auth
                    <a href="/mypage" style="color: #38bdf8; text-decoration: none;">マイページ</a>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0; display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: #38bdf8; cursor: pointer; padding: 0; font-size: 13px; font-family: inherit; text-decoration: none;">ログアウト</button>
                    </form>
                @endauth
                @guest
                    <a href="{{ route('login') }}" style="color: #38bdf8; text-decoration: none;">ログイン</a>
                @endguest
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        <footer style="margin-top: 50px; padding: 20px 0; border-top: 1px solid #2a3245; text-align: left; font-size: 12px; color: #9ca3af;">
            © HAL東京
        </footer>

    </div>
</body>
</html>