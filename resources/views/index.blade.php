@extends('layouts.base')
@section('title', '商品一覧')
@section('content')
    <section class="overflow-hidden border-b border-stone-200 bg-amber-50">
        <div class="mx-auto grid max-w-7xl gap-10 px-5 py-16 lg:grid-cols-2 lg:items-center lg:px-8 lg:py-24">
            <div><p class="mb-4 text-sm font-black tracking-[0.25em] text-amber-700">WRITE YOUR STORY</p><h1 class="text-5xl font-black leading-[1.08] tracking-tight text-slate-950 sm:text-7xl">書く時間を、<br><span class="text-amber-600">もっと好きに。</span></h1><p class="mt-6 max-w-xl text-lg leading-8 text-slate-600">手に取るたびに気分が上がる、ちょっと特別な文房具。毎日の勉強と仕事に、小さなひらめきを届けます。</p><a href="#products" class="mt-8 inline-flex rounded-full bg-slate-950 px-7 py-4 font-black text-white shadow-lg transition hover:-translate-y-0.5 hover:bg-amber-600">商品を見つける ↓</a></div>
            <div class="relative mx-auto w-full max-w-xl"><div class="absolute -inset-5 rotate-3 rounded-[2.5rem] bg-amber-300"></div><img src="{{ asset('images/products/note.png') }}" alt="おすすめのきれいなノート" class="relative aspect-[4/3] w-full -rotate-2 rounded-[2rem] object-cover shadow-2xl"></div>
        </div>
    </section>
    <section id="products" class="mx-auto max-w-7xl px-5 py-16 lg:px-8">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between"><div><p class="font-black text-amber-600">OUR PICKS</p><h2 class="text-4xl font-black tracking-tight">おすすめの商品</h2></div><form action="{{ route('products.search') }}" method="GET" class="flex max-w-md gap-2"><label for="keyword" class="sr-only">商品名を検索</label><input id="keyword" type="search" name="keyword" value="{{ request('keyword') }}" placeholder="商品名を検索" class="min-w-0 flex-1 rounded-full border border-stone-300 bg-white px-5 py-3 outline-none ring-amber-500 focus:ring-2"><button class="rounded-full bg-amber-500 px-5 py-3 font-black text-slate-950 hover:bg-amber-400">検索</button></form></div>
        @if (request('keyword'))<p class="mt-5 text-slate-600">「{{ request('keyword') }}」の検索結果 · <a href="{{ route('products.index') }}" class="font-bold text-amber-700 underline">クリア</a></p>@endif
        <div class="mt-10 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($products as $product)
                <article class="group overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl"><a href="{{ route('products.show', $product) }}" class="block overflow-hidden bg-stone-100"><img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-105"></a><div class="p-6"><div class="flex items-start justify-between gap-4"><h3 class="text-2xl font-black">{{ $product->name }}</h3><p class="shrink-0 text-xl font-black text-amber-600">¥{{ number_format($product->price) }}</p></div><p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600">{{ $product->description }}</p><a href="{{ route('products.show', $product) }}" class="mt-6 inline-flex font-black text-slate-900 underline decoration-amber-400 decoration-4 underline-offset-4">詳しく見る →</a></div></article>
            @empty
                <p class="col-span-full rounded-3xl border border-dashed border-stone-300 bg-white p-12 text-center text-slate-500">該当する商品はありません。</p>
            @endforelse
        </div>
    </section>
    @if ($news->isNotEmpty())
        <section class="bg-slate-950 text-white"><div class="mx-auto max-w-7xl px-5 py-16 lg:px-8"><p class="font-black text-amber-400">NEWS</p><h2 class="mt-1 text-3xl font-black">お知らせ</h2><div class="mt-8 grid gap-4 md:grid-cols-3">@foreach ($news as $item)<a href="{{ route('news.show', $item) }}" class="rounded-3xl border border-slate-700 bg-slate-900 p-6 transition hover:border-amber-400"><time class="text-xs font-bold text-slate-400">{{ $item->created_at->format('Y.m.d') }}</time><h3 class="mt-2 text-lg font-black">{{ $item->title }}</h3><p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-400">{{ strip_tags($item->content) }}</p></a>@endforeach</div></div></section>
    @endif
@endsection
