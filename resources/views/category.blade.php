@extends('layouts.base')

@section('title', $category->name)

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-stone-900">{{ $category->name }}</h1>

        <form action="/categories/{{ $category->slug }}" method="GET">
            <label class="flex items-center gap-2 text-sm text-stone-600">
                並び替え
                <select name="sort" onchange="this.form.submit()"
                    class="rounded-lg border border-stone-300 px-2 py-1.5 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                    <option value="new" @selected(request('sort', 'new') === 'new')>新着順</option>
                    <option value="price_asc" @selected(request('sort') === 'price_asc')>価格が安い順</option>
                    <option value="price_desc" @selected(request('sort') === 'price_desc')>価格が高い順</option>
                </select>
            </label>
        </form>
    </div>

    @if ($products->isEmpty())
        <p class="text-sm text-stone-500">このカテゴリの商品はまだありません。</p>
    @else
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($products as $product)
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
