<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="毎日のひらめきを支える、こだわりの文房具を集めたオンラインストアです。">
    <title>@yield('title', 'すごい文房具ECサイト') | すごい文房具</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-stone-50 font-sans text-slate-900 antialiased">
    <header class="sticky top-0 z-40 border-b border-stone-200 bg-stone-50/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-4 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="トップページへ">
                <img src="{{ asset('images/ec-logo.png') }}" alt="" class="h-12 w-12 object-contain">
                <span class="hidden text-lg font-black tracking-tight sm:block">すごい文房具</span>
            </a>
            <nav class="flex flex-wrap items-center justify-end gap-2 text-sm font-bold" aria-label="メインナビゲーション">
                <a href="{{ route('products.index') }}" class="rounded-full px-3 py-2 hover:bg-white">商品一覧</a>
                <a href="{{ route('wishlist.index') }}" class="rounded-full px-3 py-2 hover:bg-white">お気に入り</a>
                <a href="{{ route('cart.index') }}" class="rounded-full bg-slate-900 px-4 py-2 text-white hover:bg-amber-600">カート <span class="ml-1 text-amber-300">{{ array_sum(session('cart', [])) }}</span></a>
                @guest
                    <a href="{{ route('login') }}" class="hidden rounded-full px-3 py-2 hover:bg-white sm:block">ログイン</a>
                @else
                    <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="rounded-full px-3 py-2 hover:bg-white">ログアウト</button></form>
                @endguest
            </nav>
        </div>
    </header>
    @if (session('message'))
        <div class="mx-auto mt-5 max-w-7xl px-5 lg:px-8" role="status"><div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3 font-bold text-emerald-800">{{ session('message') }}</div></div>
    @endif
    @if ($errors->has('cart'))
        <div class="mx-auto mt-5 max-w-7xl px-5 lg:px-8" role="alert"><div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-3 font-bold text-rose-800">{{ $errors->first('cart') }}</div></div>
    @endif
    <main>@yield('content')</main>
    <footer class="mt-20 border-t border-stone-200 bg-white"><div class="mx-auto flex max-w-7xl flex-col gap-2 px-5 py-8 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between lg:px-8"><p class="font-bold text-slate-800">すごい文房具ECサイト</p><p>© {{ now()->year }} HAL東京</p></div></footer>
</body>
</html>
