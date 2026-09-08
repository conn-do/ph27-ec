<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'STATIONERY') | 文房具セレクション</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-[#f8f8f6] text-[#111111] min-h-screen flex flex-col antialiased">

    <!-- Global Header -->
    <header class="border-b border-neutral-200 bg-white sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-8 h-20 flex items-center justify-between">
            <a href="/" class="text-xl font-bold tracking-widest uppercase">文房具セレクション</a>

            <nav class="flex items-center space-x-6 text-xs font-semibold tracking-wider">
                <a href="/" class="hover:text-neutral-500 transition">商品一覧</a>
                <a href="{{ route('ranking') }}" class="hover:text-neutral-500 transition">ランキング</a>

                @auth
                    <a href="{{ route('favorites.list') }}" class="hover:text-neutral-500 transition">♥ お気に入り</a>
                    <a href="/cart" class="hover:text-neutral-500 transition">カート</a>
                    <a href="/orders" class="hover:text-neutral-500 transition">注文履歴</a>
                    <a href="{{ route('mypage') }}" class="hover:text-neutral-500 transition">マイページ</a>

                    <!-- Logout Form -->
                    <form action="/logout" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-neutral-400 hover:text-black transition cursor-pointer">
                            ログアウト
                        </button>
                    </form>
                @else
                    <a href="/cart" class="hover:text-neutral-500 transition">カート</a>
                    <a href="/login" class="hover:text-neutral-500 transition">ログイン</a>
                    <a href="/register" class="bg-black text-white px-3 py-2 hover:bg-neutral-800 transition">
                        新規会員登録
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Dynamic Content -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-8 py-12">
        @yield('content')
    </main>

    <!-- Global Footer -->
    <footer class="border-t border-neutral-200 bg-white py-8 text-center text-xs text-neutral-400 tracking-wider">
        &copy; {{ date('Y') }} STATIONERY CO. ALL RIGHTS RESERVED.
    </footer>

</body>

</html>
