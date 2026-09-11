<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'かっこいい ぶんぼうぐ') - ワクワクぶんぐや</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@500;700;800;900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/shop.js'])
</head>

<body class="min-h-screen bg-paper font-round text-ink antialiased"
    style="background-image: radial-gradient(var(--color-paper-deep) 2px, transparent 2px); background-size: 24px 24px;">

    <header class="sticky top-0 z-30 border-b-4 border-ink bg-white">
        <div class="mx-auto flex max-w-5xl flex-wrap items-center justify-between gap-3 px-4 py-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/ec-logo.png') }}" alt="ワクワクぶんぐや" class="h-10 w-auto">
                <span class="text-xl font-black tracking-tight sm:text-2xl">ワクワクぶんぐや</span>
            </a>

            <nav class="flex items-center gap-2">
                <x-nav-button :href="route('home')" color="bg-pop-yellow" emoji="🏬" label="おみせ" />

                @auth
                    <x-nav-button :href="route('favorites.index')" color="bg-pop-pink" emoji="💖" label="おきにいり" />
                @endauth

                <x-nav-button :href="route('cart.index')" color="bg-pop-orange" emoji="🛒" label="カート"
                    :badge="$cartItemCount" />

                @auth
                    <x-nav-button :href="route('mypage')" color="bg-pop-cyan" emoji="🧒" label="マイページ" />
                @else
                    <x-nav-button :href="route('login')" color="bg-pop-green" emoji="🔑" label="ログイン" />
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto w-full max-w-5xl px-4 py-8">
        <x-flash-message />
        @yield('content')
    </main>

    <footer class="mt-16 border-t-4 border-ink bg-white">
        <div class="mx-auto flex max-w-5xl flex-wrap items-center justify-between gap-4 px-4 py-6 text-sm font-bold">
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('news.index') }}" class="underline decoration-4 decoration-pop-blue underline-offset-4">おしらせ</a>
                @auth
                    <a href="{{ route('orders.index') }}" class="underline decoration-4 decoration-pop-purple underline-offset-4">かいものの きろく</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="underline decoration-4 decoration-pop-red underline-offset-4">ログアウト</button>
                    </form>
                @endauth
            </div>
            <p>© HAL東京</p>
        </div>
    </footer>
</body>

</html>
