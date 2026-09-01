<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STATIONERY | Modern Essentials</title>
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
            <a href="/" class="text-xl font-bold tracking-widest uppercase">STATIONERY</a>

            <nav class="flex items-center space-x-8 text-xs font-semibold tracking-wider">
                <a href="/" class="hover:text-neutral-500 transition">ALL PRODUCTS</a>
                <a href="/cart" class="hover:text-neutral-500 transition">CART</a>
                <a href="/orders" class="hover:text-neutral-500 transition">ORDERS</a>
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
