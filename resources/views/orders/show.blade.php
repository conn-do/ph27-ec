@extends('layouts.base')

@section('title', '注文 #'.$order->id)

@section('content')
    <div class="mx-auto max-w-4xl px-5 py-12 lg:px-8">
        <a href="{{ route('orders.index') }}" class="text-sm font-bold text-stone-500">← 注文履歴</a>
        <div class="mt-8 flex flex-wrap items-end justify-between gap-4"><div><p class="text-xs font-black tracking-[0.3em] text-teal-700">ORDER DETAIL</p><h1 class="mt-3 font-serif text-4xl font-black">注文 #{{ $order->id }}</h1></div><time class="text-sm text-stone-500">{{ $order->created_at->format('Y年m月d日 H:i') }}</time></div>
        <div class="mt-9 overflow-hidden rounded-[2rem] border border-stone-200 bg-white">
            @foreach ($order->details as $detail)
                <div class="flex items-center gap-5 border-b border-stone-100 p-5 last:border-0"><img src="{{ $detail->product->imageUrl() }}" alt="" class="size-20 rounded-2xl object-cover"><div class="flex-1"><strong class="font-serif text-lg">{{ $detail->product->name }}</strong><p class="mt-1 text-sm text-stone-500">数量 {{ $detail->quantity }}</p></div><strong>¥{{ number_format($detail->product->price * $detail->quantity) }}</strong></div>
            @endforeach
            <div class="flex items-center justify-between bg-stone-900 px-6 py-7 text-white"><span>お支払い合計</span><strong class="text-2xl">¥{{ number_format($order->total_price) }}</strong></div>
        </div>
    </div>
@endsection
