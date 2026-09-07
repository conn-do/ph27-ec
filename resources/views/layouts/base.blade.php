<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - すごい文房具サイト</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="store-body">
    <header class="store-header">
        <a class="store-brand" href="/" aria-label="すごい文房具サイト">
            <img src="{{ asset('images/ec-logo-1.png') }}" alt="すごい文房具サイト">
        </a>
        <nav class="store-nav" aria-label="メインメニュー">
            <div class="store-nav-text">
                <a href="/">商品一覧</a>
                <a href="/#news">お知らせ</a>
                <a href="#">ご利用ガイド</a>
                <a href="#">お問い合わせ</a>
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">ログアウト</button>
                    </form>
                @endauth
            </div>
            <div class="store-nav-icons">
                <a class="store-icon-link" href="/cart" aria-label="カート">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 1.9-1.4L21 8H6.2" />
                        <circle cx="10" cy="20" r="1" />
                        <circle cx="18" cy="20" r="1" />
                    </svg>
                </a>
                @auth
                    <a class="store-icon-link" href="/mypage" aria-label="マイページ">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="7" r="3.5" />
                            <path d="M4.5 21a7.5 7.5 0 0 1 15 0" />
                        </svg>
                    </a>
                @endauth
                @guest
                    <a class="store-icon-link" href="{{ route('login') }}" aria-label="ログイン">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="7" r="3.5" />
                            <path d="M4.5 21a7.5 7.5 0 0 1 15 0" />
                        </svg>
                    </a>
                @endguest
            </div>
        </nav>
    </header>
    <main class="store-main">
        @yield('content')
    </main>
    <footer class="store-footer">
        <div class="store-footer-content">
            <div class="store-footer-brand">
                <a href="/">すごい文房具サイト</a>
                <small>© HAL東京</small>
            </div>

            <nav class="store-footer-sitemap" aria-label="フッターメニュー">
                <section>
                    <h2>ショッピング</h2>
                    <a href="/">商品一覧</a>
                    <a href="/#catalog-heading">カテゴリ</a>
                    <a href="/cart">カート</a>
                </section>
                <section>
                    <h2>ご利用案内</h2>
                    <a href="#">ご利用ガイド</a>
                    <a href="#">お支払いについて</a>
                    <a href="#">配送・送料について</a>
                </section>
                <section>
                    <h2>サポート</h2>
                    <a href="#">お問い合わせ</a>
                    <a href="#">よくあるご質問</a>
                </section>
                <section>
                    <h2>会社情報</h2>
                    <a href="#">プライバシーポリシー</a>
                    <a href="#">特定商取引法に基づく表記</a>
                </section>
            </nav>
        </div>
    </footer>
</body>

</html>
