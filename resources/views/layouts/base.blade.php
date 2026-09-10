<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="毎日に小さなひらめきを届ける文房具店、PAPER & LOOP。">
    <title>@yield('title') | PAPER & LOOP</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-stone-50 text-stone-900 antialiased">
    <div class="border-b border-amber-200 bg-amber-100 px-4 py-2 text-center text-xs font-bold tracking-[0.18em] text-amber-950">
        5,000円以上のお買い物で送料無料
    </div>
    <header class="sticky top-0 z-40 border-b border-stone-200/80 bg-stone-50/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-5 px-5 py-4 lg:px-8">
            <a href="{{ route('home') }}" class="group flex items-center gap-3" aria-label="PAPER & LOOP ホーム">
                <span class="grid size-10 place-items-center rounded-full bg-teal-800 text-lg text-white transition group-hover:-rotate-6">✦</span>
                <span>
                    <span class="block font-serif text-xl font-black tracking-[0.12em]">PAPER & LOOP</span>
                    <span class="block text-[10px] font-bold tracking-[0.28em] text-stone-500">STATIONERY STORE</span>
                </span>
            </a>
            <nav class="flex items-center gap-2 text-sm font-bold" aria-label="メインナビゲーション">
                <a href="{{ route('home') }}#products" class="hidden rounded-full px-4 py-2 hover:bg-stone-200 sm:block">商品</a>
                <a href="{{ route('cart.index') }}" class="rounded-full border border-stone-300 bg-white px-4 py-2 hover:border-teal-700 hover:text-teal-800">カート</a>
                @auth
                    <a href="{{ route('mypage') }}" class="rounded-full bg-teal-800 px-4 py-2 text-white hover:bg-teal-900">マイページ</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-full bg-teal-800 px-4 py-2 text-white hover:bg-teal-900">ログイン</a>
                @endauth
            </nav>
        </div>
    </header>

    @if (session('message'))
        <div class="mx-auto mt-5 max-w-7xl px-5 lg:px-8">
            <div class="rounded-2xl border border-teal-200 bg-teal-50 px-5 py-4 text-sm font-bold text-teal-900" role="status">
                {{ session('message') }}
            </div>
        </div>
    @endif

    <main>@yield('content')</main>

    <footer class="mt-24 bg-stone-900 text-stone-300">
        <div class="mx-auto grid max-w-7xl gap-10 px-5 py-14 md:grid-cols-2 lg:px-8">
            <div>
                <p class="font-serif text-2xl font-black tracking-widest text-white">PAPER & LOOP</p>
                <p class="mt-3 max-w-md text-sm leading-7 text-stone-400">書く、描く、整える。毎日の小さな時間が少し楽しくなる道具を集めました。</p>
            </div>
            <div class="flex items-start gap-6 md:justify-end">
                <a href="{{ route('home') }}" class="text-sm hover:text-white">ホーム</a>
                <a href="{{ route('cart.index') }}" class="text-sm hover:text-white">カート</a>
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm hover:text-white">ログアウト</button>
                    </form>
                @endauth
            </div>
        </div>
        <p class="border-t border-stone-800 px-5 py-5 text-center text-xs text-stone-500">© {{ date('Y') }} PAPER & LOOP / HAL東京</p>
    </footer>
</body>
</html>
