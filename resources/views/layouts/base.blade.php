<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - すごい文房具サイト</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body.container {
            max-width: 95% !important;
            margin: 0 auto;
            padding: 1.5rem 2rem;
        }

        header nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        header nav ul {
            align-items: center;
            margin: 0;
            padding: 0;
        }

        .header-search {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
        }

        .header-search input[type="search"] {
            margin: 0;
            width: 320px;
        }

        .header-search button {
            margin: 0;
            width: auto;
            white-space: nowrap;
            padding: 0.4rem 1.2rem;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-links form {
            margin: 0;
        }

        .nav-links button {
            margin: 0;
            padding: 0.4rem 1rem;
            font-size: 0.85rem;
            width: auto;
        }

        .btn-fit {
            width: auto !important;
            display: inline-block;
        }

        /* 📁 2カラムレイアウト構造 */
        .layout-container {
            display: flex;
            gap: 2rem;
            align-items: flex-start;
        }

        /* 左側サイドバー */
        .sidebar {
            width: 260px;
            flex-shrink: 0;
            background: #ffffff;
            border: 1px solid var(--pico-muted-border-color);
            border-radius: 8px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        /* 右側メインコンテンツ */
        .main-content {
            flex: 1;
            min-width: 0;
        }

        /* アコーディオンのスタイル微調整 */
        .sidebar details summary {
            font-weight: bold;
            cursor: pointer;
            padding: 0.4rem 0.2rem;
            color: #334155;
            list-style: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar details summary::-webkit-details-marker {
            display: none;
        }

        .sidebar details summary::after {
            content: "▼";
            font-size: 0.7rem;
            color: #94a3b8;
            transition: transform 0.2s;
        }

        .sidebar details[open] summary::after {
            transform: rotate(180deg);
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-child-item a {
            text-decoration: none;
            font-size: 0.88rem;
            color: #475569;
            display: block;
            padding: 0.3rem 0.5rem;
            border-radius: 4px;
            transition: background 0.15s;
        }

        .sidebar-child-item a:hover {
            background-color: #f1f5f9;
            color: #2563eb;
        }
    </style>
</head>

<body class="container">
    <header style="border-bottom: 1px solid var(--pico-muted-border-color); padding-bottom: 1rem; margin-bottom: 2rem;">
        <nav>
            <ul>
                <li>
                    <a href="/" style="display: flex; align-items: center;">
                        <img src="{{ asset('images/ec-logo.png') }}" alt="ロゴ" width="140">
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
                <li>
                    @php
                        $cart = session()->get('cart', []);
                        $cartCount = array_sum($cart);
                    @endphp
                    <a href="/cart" style="position: relative; display: inline-block; text-decoration: none;">
                        🛒 カート
                        @if ($cartCount > 0)
                            <span style="
                                position: absolute;
                                top: -8px;
                                right: -12px;
                                background-color: #ef4444;
                                color: #ffffff;
                                font-size: 0.75rem;
                                font-weight: bold;
                                border-radius: 9999px;
                                padding: 2px 6px;
                                line-height: 1;
                                min-width: 18px;
                                text-align: center;
                            ">
                                {{ $cartCount > 99 ? '99+' : $cartCount }}
                            </span>
                        @endif
                    </a>
                </li>
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

    <div class="layout-container">
        {{-- 📁 左側：カテゴリーサイドバー --}}
        <aside class="sidebar">
            <h3 style="font-size: 1.05rem; font-weight: bold; border-bottom: 2px solid #2563eb; padding-bottom: 0.5rem; margin-bottom: 1rem; color: #1e293b;">
                📁 カテゴリーから探す
            </h3>

            <ul>
                <li style="margin-bottom: 0.5rem;">
                    <a href="/" style="text-decoration: none; font-weight: bold; color: #2563eb; display: block; padding: 0.3rem 0.2rem;">
                        🏠 すべての商品
                    </a>
                </li>

                @if (isset($sidebarCategories) && $sidebarCategories->count() > 0)
                    @foreach ($sidebarCategories as $parentCategory)
                        <li style="margin-bottom: 0.5rem;">
                            @if ($parentCategory->children->count() > 0)
                                <details open style="margin: 0; border: none; padding: 0;">
                                    <summary>
                                        {{ $parentCategory->name }}
                                    </summary>
                                    <ul style="padding-left: 0.8rem; margin: 0.3rem 0 0.5rem 0; display: flex; flex-direction: column; gap: 0.2rem;">
                                        @foreach ($parentCategory->children as $child)
                                            <li class="sidebar-child-item">
                                                <a href="/?category_id={{ $child->id }}">
                                                    └ {{ $child->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </details>
                            @else
                                <a href="/?category_id={{ $parentCategory->id }}" style="text-decoration: none; font-weight: bold; color: #334155; display: block; padding: 0.3rem 0.2rem;">
                                    {{ $parentCategory->name }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                @else
                    <p style="font-size: 0.85rem; color: #94a3b8;">カテゴリーが登録されていません。</p>
                @endif
            </ul>
        </aside>

        {{-- 📦 右側：メインコンテンツ表示エリア --}}
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <footer style="margin-top: 4rem; padding-top: 2rem; border-top: 1px solid var(--pico-muted-border-color); text-align: center;">
        <small>© HAL東京</small>
    </footer>
</body>

</html>