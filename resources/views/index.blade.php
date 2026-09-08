@extends('layouts.base')

@section('title', '商品一覧')

@section('content')

    <!-- Header Banner & Search Form -->
    <div class="mb-12 border-b border-neutral-200 pb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <span class="text-xs uppercase tracking-widest text-neutral-400 font-semibold">厳選コレクション</span>
            <h1 class="text-3xl font-bold tracking-wideset text-neutral-900 mt-1">クリエイターのための必須ツール</h1>

            <!-- Quick Feature Navigation -->
            <div class="mt-4 flex items-center gap-3 text-xs font-semibold tracking-wider">
                <a href="{{ route('ranking') }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-neutral-900 text-white hover:bg-neutral-700 transition">
                    <span>🏆</span> ランキングを見る
                </a>
                @auth
                    <a href="{{ route('favorites.list') }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-neutral-300 bg-white text-neutral-800 hover:border-black transition">
                        <span>♥</span> お気に入り一覧
                    </a>
                    <a href="{{ route('mypage') }}"
                        class="inline-flex items-center px-3 py-1.5 border border-neutral-300 bg-white text-neutral-800 hover:border-black transition">
                        マイページ
                    </a>
                @else
                    <a href="/login"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-neutral-200 bg-neutral-50 text-neutral-400 hover:text-black hover:border-neutral-400 transition">
                        <span>♡</span> お気に入り（要ログイン）
                    </a>
                @endauth
            </div>
        </div>

        <!-- Search Form -->
        <div class="flex flex-col items-end gap-2">
            <form action="/search" method="GET" class="flex gap-0">
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="文房具を検索..."
                    class="px-4 py-2.5 border border-neutral-300 text-xs focus:outline-none focus:border-black w-64 bg-white">
                <input type="submit" value="検索"
                    class="bg-black text-white px-6 py-2.5 text-xs font-semibold uppercase tracking-widest hover:bg-neutral-800 transition cursor-pointer">
            </form>

            @if (request('keyword'))
                <a href="/" class="text-xs text-neutral-500 hover:text-black underline transition">検索結果をクリア</a>
            @endif
        </div>
    </div>

    <!-- Category Filters -->
    @if (isset($categories) && count($categories) > 0)
        <div class="mb-12 flex flex-wrap items-center gap-2">
            <span class="text-xs text-neutral-400 font-semibold uppercase tracking-wider mr-2">カテゴリー:</span>
            <a href="/"
                class="px-4 py-1.5 text-xs border border-neutral-300 bg-white font-medium uppercase tracking-wider hover:bg-black hover:text-white hover:border-black transition">
                すべて
            </a>
            @foreach ($categories as $category)
                <a href="/categories/{{ $category->slug }}"
                    class="px-4 py-1.5 text-xs border border-neutral-300 bg-white font-medium uppercase tracking-wider hover:bg-black hover:text-white hover:border-black transition">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    @endif

    <!-- Product Cards Grid -->
    <section class="mb-16">
        <div class="flex items-center justify-between mb-6 border-b border-neutral-200 pb-3">
            <h2 class="text-lg font-medium tracking-tight text-neutral-900">商品一覧</h2>
            <span class="text-xs text-neutral-400 font-mono">{{ count($products) }}件</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse ($products as $product)
                <div class="bg-white border border-neutral-200 group flex flex-col p-4 transition hover:shadow-lg relative">

                    <!-- Product Image -->
                    <a href="/products/{{ $product->id }}"
                        class="w-full aspect-square bg-neutral-100 overflow-hidden mb-4 block relative">
                        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </a>

                    <!-- Favorite Button Toggle -->
                    @auth
                        <form action="{{ route('favorites.toggle_item', $product->id) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit"
                                class="text-xs font-semibold text-neutral-700 hover:text-red-500 transition cursor-pointer flex items-center gap-1">
                                @if (auth()->user()->favorites()->where('product_id', $product->id)->exists())
                                    <span class="text-red-500 text-sm">♥</span> <span
                                        class="text-[10px] text-neutral-500 uppercase">お気に入り解除</span>
                                @else
                                    <span class="text-neutral-400 text-sm">♡</span> <span
                                        class="text-[10px] text-neutral-500 uppercase">お気に入りに追加</span>
                                @endif
                            </button>
                        </form>
                    @endauth

                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-sm font-semibold text-neutral-900 group-hover:underline">
                            <a href="/products/{{ $product->id }}">{{ $product->name }}</a>
                        </h3>
                        @if (isset($product->price))
                            <span class="text-sm font-medium text-neutral-600">¥{{ number_format($product->price) }}</span>
                        @endif
                    </div>

                    @if (isset($product->description))
                        <p class="text-xs text-neutral-500 line-clamp-2 mb-4 leading-relaxed">{{ $product->description }}
                        </p>
                    @endif

                    <a href="/products/{{ $product->id }}"
                        class="mt-auto block text-center bg-neutral-900 text-white py-2.5 text-xs font-semibold uppercase tracking-widest hover:bg-neutral-700 transition">
                        商品詳細を見る
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-16 bg-white border border-neutral-200">
                    <p class="text-sm text-neutral-500">該当する商品が見つかりませんでした。</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- News Section -->
    @if (isset($news) && count($news) > 0)
        <section class="border-t border-neutral-200 pt-12 mt-12">
            <div class="mb-8">
                <h2 class="text-xs font-semibold uppercase tracking-widest text-neutral-400">NEWS</h2>
                <h3 class="text-2xl font-light tracking-tight text-neutral-900 mt-0.5">お知らせ</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($news as $item)
                    <div
                        class="bg-white border border-neutral-200 p-6 flex flex-col justify-between hover:border-neutral-400 transition">
                        <div>
                            <h4 class="text-sm font-medium text-neutral-900 mb-3 line-clamp-2">
                                <a href="/news/{{ $item->id }}" class="hover:underline">
                                    {{ $item->title }}
                                </a>
                            </h4>

                            <div class="text-xs text-neutral-500 line-clamp-3 leading-relaxed">
                                {!! $item->content !!}
                            </div>
                        </div>

                        <a href="/news/{{ $item->id }}"
                            class="mt-6 text-xs text-black font-semibold uppercase tracking-wider inline-flex items-center hover:opacity-60 transition">
                            続きを読む &rarr;
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

@endsection
