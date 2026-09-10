<html>

<head>
    <meta charset="UTF-8">

    <title>
        @yield('title') - PH27 STATIONERY
    </title>

    @vite('resources/css/app.css')

    <style>
        /* =========================
           Base
        ========================= */

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #ffffff;
            color: #1f2428;
            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Helvetica Neue",
                "Yu Gothic",
                "Hiragino Kaku Gothic ProN",
                Arial,
                sans-serif;
        }

        a {
            color: inherit;
        }

        /* =========================
           Header
        ========================= */

        .site-header {
            width: 100%;
            border-bottom: 1px solid #1f2428;
            background: #ffffff;
        }

        .header-inner {
            max-width: 1200px;
            min-height: 88px;
            margin: 0 auto;
            padding: 0 24px;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
        }

        /* Logo */

        .site-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            flex-shrink: 0;
        }

        .site-logo img {
            width: 100px;
            height: auto;
            display: block;
        }

        /* Navigation */

        .site-nav {
            display: flex;
            align-items: center;
            gap: 28px;
        }

        .site-nav a {
            position: relative;

            color: #1f2428;
            text-decoration: none;

            font-size: 12px;
            letter-spacing: 0.08em;

            padding: 10px 0;

            transition: opacity 0.25s ease;
        }

        .site-nav a::after {
            content: "";

            position: absolute;
            left: 0;
            bottom: 3px;

            width: 0;
            height: 1px;

            background: #1f2428;

            transition: width 0.25s ease;
        }

        .site-nav a:hover {
            opacity: 0.65;
        }

        .site-nav a:hover::after {
            width: 100%;
        }

        /* Logout */

        .logout-form {
            margin: 0;
            padding: 0;
        }

        .logout-button {
            appearance: none;

            border: none;
            border-radius: 0;

            background: transparent;
            color: #1f2428;

            padding: 10px 0;
            margin: 0;

            font-size: 12px;
            letter-spacing: 0.08em;

            cursor: pointer;

            transition: opacity 0.25s ease;
        }

        .logout-button:hover {
            opacity: 0.65;
            background: transparent;
        }

        /* =========================
           Main
        ========================= */

        .site-main {
            width: 100%;
        }

        /* =========================
           Footer
        ========================= */

        .site-footer {
            border-top: 1px solid #ddd;

            margin-top: 80px;

            padding: 30px 24px;

            text-align: center;

            color: #777;

            font-size: 11px;
            letter-spacing: 0.12em;
        }

        /* =========================
           Responsive
        ========================= */

        @media (max-width: 700px) {

            .header-inner {
                min-height: auto;

                padding-top: 20px;
                padding-bottom: 20px;

                align-items: flex-start;
            }

            .site-nav {
                flex-wrap: wrap;
                justify-content: flex-end;
                gap: 8px 18px;
            }

            .site-nav a,
            .logout-button {
                font-size: 11px;
            }

            .site-logo img {
                width: 85px;
            }
        }

        @media (max-width: 500px) {

            .header-inner {
                display: block;
            }

            .site-logo {
                margin-bottom: 18px;
            }

            .site-nav {
                justify-content: flex-start;
            }

            .site-nav a,
            .logout-button {
                padding: 6px 0;
            }

            .site-footer {
                margin-top: 60px;
            }
        }
    </style>
</head>


<body>

    {{-- =========================
         Header
    ========================= --}}

    <header class="site-header">

        <div class="header-inner">

            <a href="/" class="site-logo">

                <img src="{{ asset('images/ec-logo.png') }}" alt="PH27 STATIONERY">

            </a>


            <nav class="site-nav">

                <a href="/cart">
                    カートを見る
                </a>


                @auth

                    <a href="/mypage">
                        マイページ
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="logout-form">

                        @csrf

                        <button type="submit" class="logout-button">
                            ログアウト
                        </button>

                    </form>

                @endauth


                @guest

                    <a href="{{ route('login') }}">
                        ログイン
                    </a>

                @endguest

            </nav>

        </div>

    </header>


    {{-- =========================
         Main
    ========================= --}}

    <main class="site-main">

        @yield('content')

    </main>


    {{-- =========================
         Footer
    ========================= --}}

    <footer class="site-footer">

        © HAL東京

    </footer>

</body>

</html>
