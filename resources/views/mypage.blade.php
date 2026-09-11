@extends('layouts.base')

@section('title', 'マイページ')

@section('content')
    <x-page-heading emoji="🧒" color="bg-pop-cyan">
        {{ $user->name }} さんの ページ
    </x-page-heading>

    {{-- おさいふ --}}
    <div class="mb-8 rounded-3xl border-4 border-ink bg-pop-yellow p-6 shadow-block-lg">
        <p class="text-sm font-black text-ink/70">いまの おこづかい</p>
        <p class="calc-number mt-1 text-5xl sm:text-6xl">
            {{ number_format($user->allowance_balance) }}<span class="text-2xl">えん</span>
        </p>
        <a href="{{ route('wallet.index') }}"
            class="mt-4 inline-block rounded-2xl border-4 border-ink bg-white px-4 py-2 text-sm font-black shadow-block-sm block-press">
            おこづかい帳を みる →
        </a>
    </div>

    {{-- かず --}}
    <div class="mb-8 grid grid-cols-3 gap-3">
        <div class="rounded-3xl border-4 border-ink bg-white p-4 text-center shadow-block">
            <p class="text-3xl" aria-hidden="true">🧾</p>
            <p class="calc-number mt-1 text-3xl">{{ $user->orders_count }}</p>
            <p class="text-xs font-black text-ink/60">かいもの した かず</p>
        </div>
        <div class="rounded-3xl border-4 border-ink bg-white p-4 text-center shadow-block">
            <p class="text-3xl" aria-hidden="true">💖</p>
            <p class="calc-number mt-1 text-3xl">{{ $user->favorites_count }}</p>
            <p class="text-xs font-black text-ink/60">おきにいり</p>
        </div>
        <div class="rounded-3xl border-4 border-ink bg-white p-4 text-center shadow-block">
            <p class="text-3xl" aria-hidden="true">💬</p>
            <p class="calc-number mt-1 text-3xl">{{ $user->reviews_count }}</p>
            <p class="text-xs font-black text-ink/60">かいた かんそう</p>
        </div>
    </div>

    {{-- いままでに つかった おかね --}}
    <div class="mb-8 rounded-3xl border-4 border-ink bg-white p-6 shadow-block">
        <p class="text-sm font-black text-ink/60">いままでに つかった おかね</p>
        <p class="calc-number mt-1 text-4xl text-pop-red">
            {{ number_format($user->orders_sum_total_price ?? 0) }}<span class="text-xl">えん</span>
        </p>
    </div>

    {{-- さいきんの かいもの --}}
    <section class="mb-8">
        <h2 class="mb-4 flex items-center gap-2 text-2xl font-black">
            <span aria-hidden="true">🕒</span> さいきんの かいもの
        </h2>

        @forelse ($recentOrders as $order)
            <a href="{{ route('orders.show', $order) }}"
                class="mb-3 flex items-center justify-between gap-4 rounded-3xl border-4 border-ink bg-white p-4 shadow-block-sm block-press">
                <span class="font-black">{{ $order->created_at->format('n月j日') }}</span>
                <span class="calc-number text-xl text-pop-red">{{ number_format($order->total_price) }}えん</span>
            </a>
        @empty
            <x-empty-state emoji="🛒" :action-label="'おみせに いく'" :action-href="route('home')">
                まだ かいものを して いないよ。
            </x-empty-state>
        @endforelse
    </section>

    <div class="flex flex-wrap gap-3">
        <x-big-button :href="route('orders.index')" color="bg-pop-purple">かいものの きろく</x-big-button>
        <x-big-button :href="route('favorites.index')" color="bg-pop-pink">おきにいり</x-big-button>
    </div>
@endsection
