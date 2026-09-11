@extends('layouts.base')

@section('title', '商品一覧')

@section('content')

    {{-- ヒーロー --}}
    <div class="text-center pt-24 pb-20">
        <div class="text-5xl font-light leading-snug mb-6">書くことを、<br>もっと美しく。</div>
        <div class="text-base text-navy-500 mb-10">厳選した文房具だけを、静かに並べました。</div>
        <form action="/search" method="GET" class="flex items-center gap-3 bg-navy-100 rounded-full px-5 py-3 max-w-sm mx-auto">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="text-navy-500 shrink-0">
                <circle cx="11" cy="11" r="7"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="商品を検索" class="bg-transparent border-0 outline-none text-sm flex-1 min-w-0">
            <button type="submit" class="text-xs text-navy-500 hover:text-navy-950 shrink-0">検索</button>
        </form>
        @if (request('keyword'))
            <div class="mt-4">
                <a href="/" class="text-sm text-navy-500 hover:text-navy-950">検索結果をクリア</a>
            </div>
        @endif
    </div>

    {{-- カテゴリ一覧 --}}
    <div class="pb-24">
        <div class="flex flex-wrap justify-center gap-3">
            @foreach ($categories as $category)
                <a href="/categories/{{ $category->slug }}"
                    class="px-6 py-3 border border-navy-200 rounded-full text-sm hover:bg-navy-100 transition">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- 商品一覧 --}}
    <div class="pb-28">
        <div class="text-2xl font-light mb-11">おすすめの商品</div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-11">
            @foreach ($products as $product)
                <div class="flex flex-col gap-4">
                    <a href="/products/{{ $product->id }}" class="block group">
                        <div class="bg-navy-100 rounded-2xl flex items-center justify-center h-64 overflow-hidden transition group-hover:shadow-xl">
                            <img src="{{ $product->imageUrl() }}" class="max-h-full max-w-full object-contain">
                        </div>
                    </a>
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex flex-col gap-1">
                            <a href="/products/{{ $product->id }}" class="text-sm text-navy-950 hover:text-navy-500">
                                {{ $product['name'] }}
                            </a>
                            <div class="text-sm font-medium text-navy-700">¥{{ number_format($product->price) }}</div>
                        </div>
                        @auth
                            <form action="/favorites" method="post">
                                @csrf
                                <input type="hidden" name="productId" value="{{ $product->id }}">
                                <button type="submit" class="text-xs border border-navy-200 rounded-full px-3 py-1.5 text-navy-500 hover:bg-navy-100 hover:text-navy-950 transition whitespace-nowrap">
                                    お気に入り
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- お知らせ --}}
    <div class="pb-24 border-t border-navy-200 pt-20">
        <div class="text-2xl font-light mb-10">お知らせ</div>
        @foreach ($news as $item)
            <div class="py-7 border-b border-navy-200">
                <a href="/news/{{ $item->id }}" class="text-base text-navy-950 hover:text-navy-500 font-medium block mb-2">
                    {{ $item->title }}
                </a>
                <div class="text-sm text-navy-500 leading-relaxed">{!! $item->content !!}</div>
            </div>
        @endforeach
    </div>

@endsection