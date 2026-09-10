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
        <div><p class="font-black text-amber-600">OUR PICKS</p><h2 class="text-4xl font-black tracking-tight">おすすめの商品</h2></div>
        <form action="{{ route('products.search') }}#products" method="GET" class="mt-8 grid gap-4 rounded-3xl border border-stone-200 bg-white p-5 sm:grid-cols-2 lg:grid-cols-3">
            <label class="text-sm font-bold">キーワード<input type="search" name="keyword" value="{{ $filters['keyword'] ?? '' }}" maxlength="100" placeholder="商品名・説明を検索" class="mt-2 block w-full rounded-xl border border-stone-300 px-4 py-3"></label>
            <label class="text-sm font-bold">カテゴリー<select name="category" class="mt-2 block w-full rounded-xl border border-stone-300 px-4 py-3"><option value="">すべてのカテゴリー</option>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected(($filters['category'] ?? '') == $category->id)>{{ $category->name }}</option>@endforeach</select></label>
            <label class="text-sm font-bold">並び順<select name="sort" class="mt-2 block w-full rounded-xl border border-stone-300 px-4 py-3">@foreach (['newest' => '新着順', 'price_asc' => '価格の安い順', 'price_desc' => '価格の高い順', 'name' => '商品名順'] as $value => $label)<option value="{{ $value }}" @selected(($filters['sort'] ?? 'newest') === $value)>{{ $label }}</option>@endforeach</select></label>
            <label class="text-sm font-bold">最低価格（円）<input type="number" name="min_price" min="0" max="2147483647" value="{{ $filters['min_price'] ?? '' }}" class="mt-2 block w-full rounded-xl border border-stone-300 px-4 py-3"></label>
            <label class="text-sm font-bold">最高価格（円）<input type="number" name="max_price" min="0" max="2147483647" value="{{ $filters['max_price'] ?? '' }}" class="mt-2 block w-full rounded-xl border border-stone-300 px-4 py-3"></label>
            <div class="flex items-end gap-4"><button class="rounded-full bg-amber-500 px-6 py-3 font-black hover:bg-amber-400">検索</button><a href="{{ route('products.index') }}#products" class="py-3 font-bold text-slate-600 underline">クリア</a></div>
            @if ($errors->any())<ul class="text-sm text-rose-700 sm:col-span-2 lg:col-span-3" role="alert">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>@endif
        </form>
        <p class="mt-5 text-slate-600">{{ number_format($products->total()) }}件の商品</p>
        <div class="mt-10 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($products as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="col-span-full rounded-3xl border border-dashed border-stone-300 bg-white p-12 text-center text-slate-500">該当する商品はありません。</p>
            @endforelse
        </div>
        <div class="mt-8">{{ $products->links() }}</div>
    </section>
    @if ($news->isNotEmpty())
        <section class="bg-slate-950 text-white"><div class="mx-auto max-w-7xl px-5 py-16 lg:px-8"><p class="font-black text-amber-400">NEWS</p><h2 class="mt-1 text-3xl font-black">お知らせ</h2><div class="mt-8 grid gap-4 md:grid-cols-3">@foreach ($news as $item)<a href="{{ route('news.show', $item) }}" class="rounded-3xl border border-slate-700 bg-slate-900 p-6 transition hover:border-amber-400"><time class="text-xs font-bold text-slate-400">{{ $item->created_at->format('Y.m.d') }}</time><h3 class="mt-2 text-lg font-black">{{ $item->title }}</h3><p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-400">{{ strip_tags($item->content) }}</p></a>@endforeach</div></div></section>
    @endif
@endsection
