<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>やさい販売.com - 新鮮な野菜をお届け</title>
    <meta name="description" content="新鮮で美味しい野菜を全国にお届けします。">
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- ヘッダー -->
    <header class="bg-green-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-white">🥬 やさい販売.com</h1>
                </div>
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('products.index') }}"
                        class="text-white hover:text-green-200 transition-colors">商品一覧</a>
                    <a href="{{ route('cart.index') }}"
                        class="text-white hover:text-green-200 transition-colors">カート</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- メインコンテンツ -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- フッター -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <p>&copy; 2024 やさい販売.com. 新鮮な野菜をお届けします。</p>
            </div>
        </div>
    </footer>
</body>

</html>
