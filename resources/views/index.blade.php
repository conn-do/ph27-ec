@extends('layouts.base')

@section('title', '商品一覧')

@section('content')
    <div class="flex flex-col gap-10 lg:flex-row">
        <div class="flex-1">
            <form action="/search" method="GET"
                class="mb-6 flex flex-wrap items-end gap-3 rounded-xl border border-stone-200 bg-white p-4">
                <div class="min-w-40 flex-1">
                    <label class="mb-1 block text-xs font-medium text-stone-600">キーワード</label>
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="商品を検索"
                        class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-stone-600">価格（下限）</label>
                    <input type="number" name="min_price" value="{{ request('min_price') }}" min="0" placeholder="0"
                        class="w-24 rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-stone-600">価格（上限）</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" min="0" placeholder="上限なし"
                        class="w-24 rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                </div>

                <label class="mb-2 flex items-center gap-2 text-sm text-stone-600">
                    <input type="checkbox" name="in_stock_only" value="1" @checked(request('in_stock_only'))
                        class="rounded border-stone-300 text-amber-600 focus:ring-amber-500">
                    在庫ありのみ
                </label>

                <button type="submit"
                    class="rounded-lg bg-amber-600 px-5 py-2 text-sm font-medium text-white transition hover:bg-amber-700">
                    検索
                </button>
            </form>

            @if (request()->hasAny(['keyword', 'min_price', 'max_price', 'in_stock_only']))
                <a href="/" class="mb-4 inline-block text-sm text-amber-700 hover:underline">検索結果をクリア</a>
            @endif

            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-bold text-stone-900">商品一覧</h2>

                <form action="/search" method="GET">
                    <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                    <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                    <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                    <input type="hidden" name="in_stock_only" value="{{ request('in_stock_only') }}">

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
                <p class="text-sm text-stone-500">商品が見つかりませんでした。</p>
            @else
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
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
        </div>

        <aside class="w-full lg:w-64 lg:shrink-0">
            <div class="rounded-xl border border-stone-200 bg-white p-4">
                <h3 class="mb-3 text-sm font-bold text-stone-900">カテゴリ</h3>
                <ul class="space-y-1">
                    @foreach ($categories as $category)
                        <li>
                            <a href="/categories/{{ $category->slug }}"
                                class="block rounded-lg px-2 py-1.5 text-sm text-stone-600 transition hover:bg-stone-100 hover:text-stone-900">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="mt-6 rounded-xl border border-stone-200 bg-white p-4">
                <h3 class="mb-1 text-sm font-bold text-stone-900">NEWS</h3>
                <p class="mb-3 text-xs text-stone-500">お知らせ</p>

                <div class="space-y-4">
                    @foreach ($news as $item)
                        <div class="border-t border-stone-100 pt-3 first:border-t-0 first:pt-0">
                            <a href="/news/{{ $item->id }}"
                                class="text-sm font-medium text-stone-800 hover:text-amber-700">
                                {{ $item->title }}
                            </a>
                            <div class="mt-1 line-clamp-2 text-xs text-stone-500">
                                {!! $item->content !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>
@endsection
