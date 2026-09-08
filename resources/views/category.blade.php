@extends('layouts.base')

@section('title', $category->name)

@section('content')
    <div class="max-w-7xl mx-auto py-8">

        <!-- Category Header -->
        <div class="mb-10 border-b border-neutral-200 pb-6 flex items-end justify-between">
            <div>
                <a href="/"
                    class="text-xs font-semibold uppercase tracking-widest text-neutral-400 hover:text-black transition">
                    &larr; ALL PRODUCTS
                </a>
                <h1 class="text-3xl font-light tracking-tight text-neutral-900 mt-2 uppercase">
                    {{ $category->name }}
                </h1>
            </div>
            <span class="text-xs font-mono text-neutral-400">
                {{ count($category->products) }} items
            </span>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse ($category->products as $product)
                <div class="bg-white border border-neutral-200 group flex flex-col p-4 transition hover:shadow-lg">
                    <!-- Product Image -->
                    <a href="/products/{{ $product->id }}"
                        class="w-full aspect-square bg-neutral-100 overflow-hidden mb-4 block relative">
                        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </a>

                    <!-- Product Name & Price -->
                    <div class="flex justify-between items-start mb-2">
                        <h2 class="text-sm font-semibold text-neutral-900 group-hover:underline">
                            <a href="/products/{{ $product->id }}">{{ $product->name }}</a>
                        </h2>
                        @if (isset($product->price))
                            <span class="text-sm font-medium text-neutral-600">¥{{ number_format($product->price) }}</span>
                        @endif
                    </div>

                    @if (isset($product->description))
                        <p class="text-xs text-neutral-500 line-clamp-2 mb-4 leading-relaxed">{{ $product->description }}
                        </p>
                    @endif

                    <!-- CTA Button -->
                    <a href="/products/{{ $product->id }}"
                        class="mt-auto block text-center bg-neutral-900 text-white py-2.5 text-xs font-semibold uppercase tracking-widest hover:bg-neutral-700 transition">
                        View Product
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-16 bg-white border border-neutral-200">
                    <p class="text-sm text-neutral-500">このカテゴリーにはまだ商品が登録されていません。</p>
                </div>
            @endforelse
        </div>

    </div>
@endsection
