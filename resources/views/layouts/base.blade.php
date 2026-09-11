<html>

<head>
    <meta charset="UTF-8">
    <title>@yield('title') - YOHaku</title>

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css"> --}}

    <script>
        (function(d) {
            var config = {
                kitId: 'tji4ock',
                scriptTimeout: 3000,
                async: true
            },
            h = d.documentElement,
            t = setTimeout(function() {
                h.className = h.className.replace(/\bwf-loading\b/g, "") + " wf-inactive";
            }, config.scriptTimeout),
            tk = d.createElement("script"),
            f = false,
            s = d.getElementsByTagName("script")[0],
            a;

            h.className += " wf-loading";
            tk.src = 'https://use.typekit.net/' + config.kitId + '.js';
            tk.async = true;
            tk.onload = tk.onreadystatechange = function() {
                a = this.readyState;

                if (f || a && a != "complete" && a != "loaded") {
                    return;
                }

                f = true;
                clearTimeout(t);

                try {
                    Typekit.load(config);
                } catch (e) {}
            };

            s.parentNode.insertBefore(tk, s);
        })(document);
    </script>

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    @vite(['resources/css/app.css', 'resources/js/main.js'])

    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
/>

</head>

<body>

    <header class="header">

        {{-- 左側ナビゲーション --}}
        <nav class="header-left">
            <a href="{{ url('/#about') }}">ABOUT</a>
            <a href="{{ url('/#category') }}">CATEGORY</a>
            <a href="{{ url('/#products') }}">PRODUCTS</a>
            <a href="{{ url('/#news') }}">NEWS</a>
        </nav>

        {{-- 中央ロゴ --}}
        <a href="/" class="header-logo">
            <img src="{{ asset('images/ec-logo.png') }}" alt="YOHaku">
        </a>

        {{-- 右側ナビゲーション --}}
        <nav class="header-right">

            {{-- 検索 --}}
            <button type="button" class="header-icon header-search-button" aria-label="検索">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="10.5" cy="10.5" r="6.5"></circle>
                    <line x1="15.5" y1="15.5" x2="21" y2="21"></line>
                </svg>
            </button>

            {{-- お気に入り --}}
            @auth
                <a href="/favorites" class="header-icon" aria-label="お気に入り">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M20.8 8.8c0 5.2-8.8 10.2-8.8 10.2S3.2 14 3.2 8.8A4.8 4.8 0 0 1 12 6.2a4.8 4.8 0 0 1 8.8 2.6Z"></path>
                    </svg>
                </a>
            @endauth

            {{-- カート --}}
            <a href="/cart" class="header-icon" aria-label="カート">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3 4h2l2.2 11.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 1.9-1.4L21 8H6"></path>
                    <circle cx="9" cy="20" r="1"></circle>
                    <circle cx="18" cy="20" r="1"></circle>
                </svg>

                @if (count(session('cart', [])) > 0)
                    <span class="cart-count">
                        {{ count(session('cart', [])) }}
                    </span>
                @endif
            </a>

            {{-- マイページ / ログイン --}}
            @auth
                <a href="/mypage" class="header-icon" aria-label="マイページ">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="8" r="3.5"></circle>
                        <path d="M5 20c.8-3.2 3.1-5 7-5s6.2 1.8 7 5"></path>
                    </svg>
                </a>
            @else
                <a href="{{ route('login') }}" class="header-icon" aria-label="ログイン">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="8" r="3.5"></circle>
                        <path d="M5 20c.8-3.2 3.1-5 7-5s6.2 1.8 7 5"></path>
                    </svg>
                </a>
            @endauth

        </nav>

    </header>

    <div class="search-panel">
        <form action="/search" method="GET">
            <input
                type="text"
                name="keyword"
                placeholder="商品を検索"
                value="{{ request('keyword') }}"
            >

            <button type="submit" aria-label="検索">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="10.5" cy="10.5" r="6.5"></circle>
                    <line x1="15.5" y1="15.5" x2="21" y2="21"></line>
                </svg>
            </button>
        </form>
    </div>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="footer-main">
            <div class="footer-column footer-brand">
                <a href="{{ url('/') }}" class="footer-logo">
                    <img src="{{ asset('images/ec-logo-white.png') }}" alt="YOHaku">
                </a>
            </div>

            <div class="footer-column footer-nav">
                <nav>
                    <a href="{{ url('/#about') }}">ABOUT</a>
                    <a href="{{ url('/#products') }}">PRODUCTS</a>
                    <a href="{{ url('/#category') }}">CATEGORY</a>
                </nav>
            </div>

            <div class="footer-column footer-company">
                <div>
                    <p class="footer-company-name">YOHaku stationery</p>
                    <p class="footer-company-text">
                        Tokyo, Japan
                    </p>
                </div>

                <p class="footer-company-mail">
                    <a href="mailto:contact@yohaku.jp">contact@yohaku.jp</a>
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            <span>© YOHaku</span>
            <span>日常に、余白を。</span>
        </div>
    </footer>


<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body>

</html>