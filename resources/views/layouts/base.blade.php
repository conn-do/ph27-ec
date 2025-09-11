<html>

<head>
    @vite(['resources/css/app.css'])
</head>

<body>
    <div class="flex justify-between items-center p-4 m-4 rounded-full shadow-lg">
        <h1 class="text-2xl font-bold">野菜販売.com</h1>
        <div class="flex items-center space-x-4">
            <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-green-600 transition-colors font-medium">
                商品一覧
            </a>
            <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-green-600 transition-colors font-medium">
                カート
            </a>
        </div>
    </div>
    <div class="p-4 m-4">
        @yield('content')
    </div>
</body>

</html>