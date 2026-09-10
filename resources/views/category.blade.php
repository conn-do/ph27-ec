@extends('layouts.base')

@section('title', $category->name)

@section('content')
    <div class="mx-auto max-w-7xl px-5 py-12 lg:px-8">
        <a href="{{ route('home') }}" class="text-sm font-bold text-stone-500 hover:text-teal-800">← すべての商品</a>
        <div class="mt-7 rounded-[2rem] bg-amber-100 p-8 md:p-12"><p class="text-xs font-black tracking-[0.3em] text-amber-800">CATEGORY</p><h1 class="mt-3 font-serif text-4xl font-black">{{ $category->name }}</h1><p class="mt-3 text-sm text-stone-600">{{ $category->products->count() }}点のアイテム</p></div>
        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($category->products as $product)<x-product-card :product="$product" />@empty<p class="text-stone-500">商品はまだありません。</p>@endforelse
        </div>
    </div>
@endsection
