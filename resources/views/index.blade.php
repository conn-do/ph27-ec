@extends('layouts.base')

@section('title', '文房具との新しい出会い')

@section('content')
    <section class="mx-auto max-w-7xl px-5 pt-10 lg:px-8 lg:pt-16">
        <div class="relative overflow-hidden rounded-[2.5rem] bg-teal-900 px-7 py-14 text-white md:px-14 md:py-20">
            <div class="absolute -right-20 -top-32 size-80 rounded-full border-[48px] border-amber-300/20"></div>
            <div class="absolute -bottom-20 right-1/4 size-48 rotate-12 rounded-[3rem] bg-rose-300/15"></div>
            <div class="relative max-w-2xl">
                <p class="text-xs font-black tracking-[0.35em] text-amber-300">TOOLS FOR YOUR IDEAS</p>
                <h1 class="mt-5 font-serif text-4xl font-black leading-tight md:text-6xl">ひらめきのそばに、<br>お気に入りの文房具を。</h1>
                <p class="mt-6 max-w-xl text-sm leading-7 text-teal-100 md:text-base">机に向かう時間をもっと心地よく。使うたびに愛着が増す、まじめで少し楽しい道具を届けます。</p>
                <a href="#products" class="mt-8 inline-flex rounded-full bg-amber-300 px-6 py-3 text-sm font-black text-stone-900 transition hover:bg-amber-200">商品を見つける →</a>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 py-12 lg:px-8">
        <div class="flex flex-wrap items-center gap-3">
            <span class="mr-2 text-xs font-black tracking-widest text-stone-500">CATEGORY</span>
            @foreach ($categories as $category)
                <a href="{{ route('categories.show', $category) }}" class="rounded-full border border-stone-300 bg-white px-5 py-2 text-sm font-bold transition hover:border-teal-700 hover:bg-teal-50 hover:text-teal-800">{{ $category->name }}</a>
            @endforeach
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 lg:px-8">
        <form action="{{ route('products.search') }}" method="GET" class="flex gap-3 rounded-3xl border border-stone-200 bg-white p-3 shadow-sm">
            <label for="keyword" class="sr-only">商品を検索</label>
            <input id="keyword" type="search" name="keyword" value="{{ request('keyword') }}" placeholder="商品名から探す" class="min-w-0 flex-1 rounded-2xl border-0 bg-stone-100 px-5 py-3 outline-none ring-teal-700 focus:ring-2">
            <button class="rounded-2xl bg-stone-900 px-6 py-3 text-sm font-black text-white hover:bg-teal-800">検索</button>
        </form>
    </section>

    @if ($rankingProducts->isNotEmpty())
        <section class="mx-auto max-w-7xl px-5 py-16 lg:px-8">
            <div class="mb-7 flex items-end justify-between">
                <div><p class="text-xs font-black tracking-[0.3em] text-amber-700">BEST SELLERS</p><h2 class="mt-2 font-serif text-3xl font-black">みんなの人気もの</h2></div>
                <p class="hidden text-sm text-stone-500 md:block">販売数から集計したランキング</p>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-5">
                @foreach ($rankingProducts as $product)
                    <x-product-card :product="$product" :rank="$loop->iteration" />
                @endforeach
            </div>
        </section>
    @endif

    <section id="products" class="mx-auto max-w-7xl px-5 py-8 lg:px-8">
        <div class="mb-7">
            <p class="text-xs font-black tracking-[0.3em] text-teal-700">COLLECTION</p>
            <h2 class="mt-2 font-serif text-3xl font-black">{{ request('keyword') ? '「'.request('keyword').'」の検索結果' : 'すべての商品' }}</h2>
        </div>
        @if ($products->isEmpty())
            <div class="rounded-3xl border border-dashed border-stone-300 bg-white px-6 py-16 text-center text-stone-500">該当する商品が見つかりませんでした。</div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($products as $product)<x-product-card :product="$product" />@endforeach
            </div>
        @endif
    </section>

    @if ($news->isNotEmpty())
        <section class="mx-auto max-w-7xl px-5 py-20 lg:px-8">
            <div class="grid gap-8 rounded-[2rem] bg-amber-100 p-7 md:grid-cols-[0.7fr_1.3fr] md:p-12">
                <div><p class="text-xs font-black tracking-[0.3em] text-amber-800">JOURNAL</p><h2 class="mt-3 font-serif text-3xl font-black">お知らせ</h2><p class="mt-4 text-sm leading-7 text-stone-600">お店からの新商品やイベントのお知らせです。</p></div>
                <div class="divide-y divide-amber-300/70">
                    @foreach ($news as $item)
                        <a href="{{ route('news.show', $item) }}" class="flex items-center justify-between gap-4 py-5 first:pt-0 last:pb-0">
                            <span><time class="text-xs text-stone-500">{{ $item->created_at?->format('Y.m.d') }}</time><strong class="mt-1 block">{{ $item->title }}</strong></span><span aria-hidden="true">→</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
