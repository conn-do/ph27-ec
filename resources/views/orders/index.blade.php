@extends('layouts.base')

@section('title', '注文履歴')

@section('content')
    <div class="mx-auto max-w-5xl px-5 py-12 lg:px-8">
        <a href="{{ route('mypage') }}" class="text-sm font-bold text-stone-500">← マイページ</a>
        <p class="mt-8 text-xs font-black tracking-[0.3em] text-teal-700">ORDER HISTORY</p><h1 class="mt-3 font-serif text-4xl font-black">注文履歴</h1>
        <div class="mt-9 space-y-4">
            @forelse ($orders as $order)
                <a href="{{ route('orders.show', $order) }}" class="grid gap-4 rounded-3xl border border-stone-200 bg-white p-6 transition hover:border-teal-700 sm:grid-cols-4 sm:items-center">
                    <span><small class="block text-xs text-stone-400">注文番号</small><strong>#{{ $order->id }}</strong></span><span><small class="block text-xs text-stone-400">注文日</small>{{ $order->created_at->format('Y.m.d') }}</span><span><small class="block text-xs text-stone-400">商品数</small>{{ $order->details_count }}点</span><span class="sm:text-right"><small class="block text-xs text-stone-400">合計</small><strong>¥{{ number_format($order->total_price) }}</strong></span>
                </a>
            @empty
                <div class="rounded-3xl border border-dashed border-stone-300 bg-white px-6 py-16 text-center text-stone-500">注文履歴はまだありません。</div>
            @endforelse
        </div>
    </div>
@endsection
