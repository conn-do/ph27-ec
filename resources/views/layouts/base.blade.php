<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - すごい文房具サイト</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-stone-50 text-stone-800 antialiased flex flex-col">
    <header class="sticky top-0 z-10 border-b border-stone-200 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-5xl items-center justify-between gap-4 px-4 py-3">
            <a href="/" class="flex items-center gap-2 shrink-0">
                <img src="{{ asset('images/ec-logo.png') }}" width="36" height="36" class="rounded-md">
                <span class="hidden text-lg font-bold tracking-tight text-stone-900 sm:inline">すごい文房具サイト</span>
            </a>

            <nav class="flex items-center gap-4 text-sm font-medium text-stone-600">
                <a href="/" class="rounded-lg px-3 py-2 transition hover:bg-stone-100 hover:text-stone-900">
                    ホーム
                </a>
                <a href="/cart" class="rounded-lg px-3 py-2 transition hover:bg-stone-100 hover:text-stone-900">
                    カートを見る
                </a>
                @auth
                    <a href="/mypage" class="rounded-lg px-3 py-2 transition hover:bg-stone-100 hover:text-stone-900">
                        マイページ
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="rounded-lg px-3 py-2 transition hover:bg-stone-100 hover:text-stone-900">
                            ログアウト
                        </button>
                    </form>
                @endauth
                @guest
                    <a href="{{ route('login') }}"
                        class="rounded-lg bg-amber-600 px-4 py-2 text-white shadow-sm transition hover:bg-amber-700">
                        ログイン
                    </a>
                @endguest
            </nav>
        </div>
    </header>

    <main class="mx-auto w-full max-w-5xl flex-1 px-4 py-8">
        @if (session('message'))
            <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                {!! session('message') !!}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="border-t border-stone-200 bg-white py-6 text-center text-sm text-stone-500">
        <nav class="mb-3 flex flex-wrap items-center justify-center gap-x-4 gap-y-1">
            <a href="/faq" class="hover:text-stone-900 hover:underline">よくあるご質問</a>
            <a href="/contact" class="hover:text-stone-900 hover:underline">お問い合わせ</a>
            <a href="/terms" class="hover:text-stone-900 hover:underline">利用規約</a>
            <a href="/privacy" class="hover:text-stone-900 hover:underline">プライバシーポリシー</a>
            <a href="/legal" class="hover:text-stone-900 hover:underline">特定商取引法に基づく表記</a>
        </nav>
        © HAL東京
    </footer>
</body>

</html>
