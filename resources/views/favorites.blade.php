@extends('layouts.base')

@section('title', 'お気に入り一覧')

@section('content')
    <div class="max-w-5xl mx-auto py-8">

        <!-- Header -->
        <div class="mb-8 border-b border-neutral-200 pb-4 flex items-center justify-between">
            <div>
                <a href="/"
                    class="text-xs font-semibold uppercase tracking-widest text-neutral-400 hover:text-black transition">
                    &larr; HOME
                </a>
                <h1 class="text-2xl font-light tracking-tight text-neutral-900 uppercase mt-2">
                    ♥ FAVORITES
                </h1>
            </div>
            <span class="text-xs text-neutral-400 font-mono">{{ count($favorites) }} saved items</span>
        </div>

        <!-- Favorite Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse ($favorites as $product)
                <div class="bg-white border border-neutral-200 group flex flex-col p-4 transition hover:shadow-lg relative">

                    <a href="/products/{{ $product->id }}"
                        class="w-full aspect-square bg-neutral-100 overflow-hidden mb-4 block relative">
                        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </a>

                    <div class="flex justify-between items-start mb-2">
                        <h2 class="text-sm font-semibold text-neutral-900 group-hover:underline">
                            <a href="/products/{{ $product->id }}">{{ $product->name }}</a>
                        </h2>
                        <span class="text-sm font-medium text-neutral-600">¥{{ number_format($product->price) }}</span>
                    </div>

                    <p class="text-xs text-neutral-500 line-clamp-2 mb-4 leading-relaxed">
                        {{ $product->description }}
                    </p>

                    <div class="mt-auto space-y-2">
                        <a href="/products/{{ $product->id }}"
                            class="block text-center bg-black text-white py-2 text-xs font-semibold uppercase tracking-widest hover:bg-neutral-800 transition">
                            View Product
                        </a>

                        <!-- Remove Favorite Action -->
                        <form action="{{ route('favorites.toggle_item', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full py-1.5 text-[10px] text-red-500 font-semibold uppercase tracking-wider hover:underline text-center cursor-pointer">
                                ✕ 削除する
                            </button>
                        </form>
                    </div>

                </div>
            @empty
                <div class="col-span-full text-center py-16 bg-white border border-neutral-200">
                    <p class="text-xs text-neutral-500">お気に入りに登録された商品はありません。</p>
                </div>
            @endforelse
        </div>

    </div>
@endsection
