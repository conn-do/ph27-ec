@extends('layouts.base')

@section('title', '人気ランキング')

@section('content')
    <div class="max-w-5xl mx-auto py-8">

        <!-- Navigation Breadcrumb & Header -->
        <div class="mb-8 border-b border-neutral-200 pb-4 flex items-center justify-between">
            <div>
                <a href="/"
                    class="text-xs font-semibold uppercase tracking-widest text-neutral-400 hover:text-black transition">
                    &larr; ホームへ戻る
                </a>
                <h1 class="text-2xl font-light tracking-tight text-neutral-900 uppercase mt-2">
                    🏆 人気商品ランキング
                </h1>
            </div>
            <span class="text-xs text-neutral-400 font-mono">上位 {{ count($rankedProducts) }} 件</span>
        </div>

        <!-- Ranking List -->
        <div class="space-y-4">
            @forelse ($rankedProducts as $index => $product)
                <div
                    class="bg-white border border-neutral-200 p-4 flex items-center gap-6 hover:border-neutral-400 transition">

                    <!-- Rank Badge -->
                    <div class="w-12 text-center flex-shrink-0">
                        @if ($index === 0)
                            <span class="text-xl font-bold text-amber-500">1位</span>
                        @elseif ($index === 1)
                            <span class="text-xl font-bold text-neutral-400">2位</span>
                        @elseif ($index === 2)
                            <span class="text-xl font-bold text-amber-700">3位</span>
                        @else
                            <span class="text-sm font-semibold text-neutral-500">{{ $index + 1 }}位</span>
                        @endif
                    </div>

                    <!-- Product Image -->
                    <a href="/products/{{ $product->id }}" class="w-20 h-20 bg-neutral-100 flex-shrink-0 overflow-hidden">
                        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </a>

                    <!-- Details -->
                    <div class="flex-grow">
                        <h2 class="text-sm font-semibold text-neutral-900 mb-1">
                            <a href="/products/{{ $product->id }}" class="hover:underline">{{ $product->name }}</a>
                        </h2>
                        <p class="text-xs font-medium text-neutral-600">
                            ¥{{ number_format($product->price) }} <span class="text-[10px] text-neutral-400">（税込）</span>
                        </p>
                    </div>

                    <!-- Action Button -->
                    <div class="flex-shrink-0">
                        <a href="/products/{{ $product->id }}"
                            class="bg-black text-white px-5 py-2.5 text-xs font-semibold uppercase tracking-widest hover:bg-neutral-800 transition">
                            商品詳細を見る
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white border border-neutral-200">
                    <p class="text-xs text-neutral-500">ランキングデータがありません。</p>
                </div>
            @endforelse
        </div>

    </div>
@endsection
