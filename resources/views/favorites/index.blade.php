@extends('layouts.base')

@section('title', 'お気に入り')

@section('content')
    <h1 class="mb-6 text-xl font-bold text-stone-900">お気に入り</h1>

    @if ($favorites->isEmpty())
        <p class="text-sm text-stone-500">お気に入りに追加した商品がありません。</p>
    @else
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($favorites as $favorite)
                <a href="/products/{{ $favorite->product->id }}"
                    class="group overflow-hidden rounded-xl border border-stone-200 bg-white shadow-sm transition hover:shadow-md">
                    <div class="aspect-square overflow-hidden bg-stone-100">
                        <img src="{{ $favorite->product->imageUrl() }}" alt="{{ $favorite->product->name }}"
                            class="h-full w-full object-cover transition group-hover:scale-105">
                    </div>
                    <div class="p-3">
                        <p class="truncate text-sm font-medium text-stone-800">{{ $favorite->product->name }}</p>
                        <p class="mt-1 text-sm font-bold text-amber-700">{{ number_format($favorite->product->price) }}円</p>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
