<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - すごい文房具サイト</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* コンテンツ全体の幅を適切に絞り込んで中央寄せ */
        body.container {
            max-width: 960px;
            margin: 0 auto;
            padding: 1rem 1.5rem;
        }

        header nav ul {
            align-items: center;
        }

        .header-search {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
        }

        .header-search input[type="search"] {
            margin: 0;
            width: 200px;
        }

        .header-search button {
            margin: 0;
            width: auto;
            white-space: nowrap;
            padding: 0.4rem 0.9rem;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .nav-links form {
            margin: 0;
        }

        .nav-links button {
            margin: 0;
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
            width: auto;
        }

        /* フォームボタンが横に広がりすぎないように制限 */
        .btn-fit {
            width: auto !important;
            display: inline-block;
        }
    </style>
</head>

<body class="container">
    <header style="border-bottom: 1px solid var(--pico-muted-border-color); padding-bottom: 1rem; margin-bottom: 2rem;">
        <nav>
            <ul>
                <li>
                    <a href="/" style="display: flex; align-items: center;">
                        <img src="{{ asset('images/ec-logo.png') }}" alt="ロゴ" width="120">
                    </a>
                </li>
            </ul>
            <ul>
                <li>
                    <form action="/search" method="GET" class="header-search">
                        <input type="search" name="keyword" value="{{ request('keyword') }}" placeholder="商品を検索...">
                        <button type="submit">検索</button>
                    </form>
                </li>
            </ul>
            <ul class="nav-links">
                <li><a href="/cart">🛒 カート</a></li>
                @auth
                    <li><a href="/mypage">👤 マイページ</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="secondary outline">ログアウト</button>
                        </form>
                    </li>
                @endauth
                @guest
                    <li><a href="{{ route('login') }}" role="button" class="outline">ログイン</a></li>
                @endguest
            </ul>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer style="margin-top: 4rem; padding-top: 2rem; border-top: 1px solid var(--pico-muted-border-color); text-align: center;">
        <small>© HAL東京</small>
    </footer>
</body>

</html>