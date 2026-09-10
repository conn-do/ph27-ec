@extends('layouts.base')

@section('title', 'ご注文ありがとうございます')

@section('content')
    <div class="mx-auto max-w-3xl px-5 py-20 text-center lg:px-8">
        <div class="mx-auto grid size-20 place-items-center rounded-full bg-teal-100 text-4xl text-teal-800">✓</div><p class="mt-7 text-xs font-black tracking-[0.3em] text-teal-700">ORDER COMPLETE</p><h1 class="mt-3 font-serif text-4xl font-black">ご注文ありがとうございます</h1><p class="mt-5 leading-7 text-stone-600">注文番号は <strong>#{{ $order->id }}</strong> です。大切にお届けします。</p>
        <div class="mt-9 flex flex-wrap justify-center gap-3"><a href="{{ route('orders.show', $order) }}" class="rounded-full bg-teal-800 px-6 py-3 text-sm font-black text-white">注文内容を見る</a><a href="{{ route('home') }}" class="rounded-full border border-stone-300 bg-white px-6 py-3 text-sm font-bold">買い物を続ける</a></div>
    </div>
@endsection
