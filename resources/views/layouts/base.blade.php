<html>

<head>
    <meta charset="UTF-8">
    <title>@yield('title') - すごい文房具サイト</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="container">
    <header>
        <h1>すごい文房具サイト</h1>
    </header>
    <main>
        @yield('content')
    </main>
    <footer>
        © HAL東京
    </footer>
</body>

</html>
