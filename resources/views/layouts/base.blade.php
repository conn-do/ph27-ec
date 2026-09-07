<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - すごい文具店</title>
    <link rel="stylesheet" href="{{ asset('css/storefront.css') }}">
</head>
<body>
    <div class="announcement">書く、描く、考える。毎日をちょっと豊かにする文房具。</div>
    <header class="site-header">
        <a class="brand" href="{{ route('home') }}">すごい文具店<span>STATIONERY & EVERYDAY</span></a>
        <nav aria-label="メインメニュー">
            <a href="{{ route('home') }}">商品を探す</a>
            @auth
                <a href="/orders">注文履歴</a>
                <a href="/mypage">マイページ</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-button" type="submit">ログアウト</button></form>
            @else
                <a href="{{ route('login') }}">ログイン</a>
            @endauth
            <a class="cart-link" href="{{ route('cart.index') }}">カート <span>{{ array_sum(session('cart', [])) }}</span></a>
        </nav>
    </header>
    <main class="container">
        @if (session('message'))<div class="notice" role="status">{{ session('message') }}</div>@endif
        @if ($errors->any())
            <div class="notice error" role="alert">@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
        @endif
        @yield('content')
    </main>
    <footer class="site-footer"><div class="brand">すごい文具店<span>小さな道具から、大きなアイデアを。</span></div><p>© {{ date('Y') }} すごい文具店 · HAL東京 学習制作サイト</p></footer>
</body>
</html>
