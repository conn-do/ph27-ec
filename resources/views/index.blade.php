@extends('layouts.base')

@section('title', '商品一覧')

@section('content')

    <!-- Header Banner & Search Form -->
    <div class="mb-12 border-b border-neutral-200 pb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <span class="text-xs uppercase tracking-widest text-neutral-400 font-semibold">Curated Collection</span>
            <h1 class="text-3xl font-light tracking-tight text-neutral-900 mt-1">ESSENTIAL TOOLS FOR CREATIVES</h1>
        </div>

        <!-- Search Form -->
        <div class="flex flex-col items-end gap-2">
            <form action="/search" method="GET" class="flex gap-0">
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Search stationery..."
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
            <span class="text-xs text-neutral-400 font-semibold uppercase tracking-wider mr-2">Categories:</span>
            <a href="/"
                class="px-4 py-1.5 text-xs border border-neutral-300 bg-white font-medium uppercase tracking-wider hover:bg-black hover:text-white hover:border-black transition">
                ALL
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
            <h2 class="text-lg font-medium tracking-tight text-neutral-900">PRODUCTS</h2>
            <span class="text-xs text-neutral-400 font-mono">{{ count($products) }} items</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse ($products as $product)
                <div class="bg-white border border-neutral-200 group flex flex-col p-4 transition hover:shadow-lg">
                    <a href="/products/{{ $product->id }}"
                        class="w-full aspect-square bg-neutral-100 overflow-hidden mb-4 block relative">
                        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </a>

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
                        View Product
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
                            Read More &rarr;
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

@endsection
