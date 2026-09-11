@extends('layouts.base')

@section('title', $category->name)

@section('content')
    <h1 class="mb-6 text-xl font-bold text-stone-900">{{ $category->name }}</h1>

    @if ($category->products->isEmpty())
        <p class="text-sm text-stone-500">このカテゴリの商品はまだありません。</p>
    @else
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($category->products as $product)
                <a href="/products/{{ $product->id }}"
                    class="group overflow-hidden rounded-xl border border-stone-200 bg-white shadow-sm transition hover:shadow-md">
                    <div class="aspect-square overflow-hidden bg-stone-100">
                        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}"
                            class="h-full w-full object-cover transition group-hover:scale-105">
                    </div>
                    <div class="p-3">
                        <p class="truncate text-sm font-medium text-stone-800">{{ $product->name }}</p>
                        <p class="mt-1 text-sm font-bold text-amber-700">{{ number_format($product->price) }}円</p>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
