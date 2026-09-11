@extends('layouts.base')

@section('title', $activeCategory?->name ?? 'おみせ')

@section('content')
    <x-page-heading emoji="🏬" color="bg-pop-yellow">
        なにを かおうかな？
        <x-slot:description>すきな しょうひんを えらんで カートに いれてね。</x-slot:description>
    </x-page-heading>

    {{-- カテゴリ えらび --}}
    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('home') }}"
            class="rounded-2xl border-4 border-ink px-4 py-2 text-sm font-black shadow-block-sm block-press {{ $activeCategory ? 'bg-white' : 'bg-ink text-white' }}">
            ぜんぶ
        </a>

        @foreach ($categories as $category)
            <a href="{{ route('home', ['category' => $category->slug]) }}"
                class="rounded-2xl border-4 border-ink px-4 py-2 text-sm font-black shadow-block-sm block-press {{ $activeCategory?->is($category) ? 'bg-ink text-white' : 'bg-white' }}">
                <span aria-hidden="true">{{ $category->emoji }}</span> {{ $category->name }}
                <span class="text-ink/50">{{ $category->products_count }}</span>
            </a>
        @endforeach
    </div>

    {{-- さがす ＆ ならびかえ --}}
    <div class="mb-8 flex flex-wrap items-center gap-3">
        <form action="{{ route('products.search') }}" method="GET" class="flex grow gap-2">
            <input type="search" name="keyword" value="{{ $keyword }}" placeholder="なまえで さがす"
                class="w-full min-w-0 rounded-2xl border-4 border-ink bg-white px-4 py-3 text-base font-bold placeholder:text-ink/40 focus:ring-4 focus:ring-pop-blue focus:outline-none">
            @if ($activeCategory)
                <input type="hidden" name="category" value="{{ $activeCategory->slug }}">
            @endif
            <button type="submit"
                class="shrink-0 rounded-2xl border-4 border-ink bg-pop-blue px-5 py-3 text-base font-black text-white shadow-block-sm block-press">
                🔍 さがす
            </button>
        </form>

        <form action="{{ url()->current() }}" method="GET">
            @foreach (request()->except('sort', 'page') as $name => $value)
                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
            @endforeach
            <select name="sort" onchange="this.form.submit()"
                class="rounded-2xl border-4 border-ink bg-white px-4 py-3 text-base font-black focus:ring-4 focus:ring-pop-blue focus:outline-none">
                <option value="">あたらしい じゅん</option>
                <option value="cheap" @selected($sort === 'cheap')>やすい じゅん</option>
                <option value="expensive" @selected($sort === 'expensive')>たかい じゅん</option>
                <option value="popular" @selected($sort === 'popular')>にんき じゅん</option>
            </select>
        </form>
    </div>

    @if ($keyword !== '')
        <p class="mb-4 text-base font-black">
            「{{ $keyword }}」の けんさくけっか： {{ $products->total() }}こ
            <a href="{{ route('home') }}" class="ml-2 underline decoration-4 decoration-pop-red underline-offset-4">クリア</a>
        </p>
    @endif

    @if ($products->isEmpty())
        <x-empty-state emoji="🔍" :action-label="'おみせに もどる'" :action-href="route('home')">
            しょうひんが みつからなかったよ。
        </x-empty-state>
    @else
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @endif
@endsection
