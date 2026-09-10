@extends('layouts.base')
@section('title', '注文完了')
@section('content')
    <section class="mx-auto max-w-3xl px-5 py-20 text-center lg:px-8"><div class="mx-auto flex size-24 items-center justify-center rounded-full bg-emerald-100 text-5xl">✓</div><p class="mt-8 font-black tracking-widest text-emerald-700">THANK YOU!</p><h1 class="mt-2 text-5xl font-black tracking-tight">注文が完了しました！</h1><p class="mt-5 text-lg text-slate-600">ご注文ありがとうございます。大切にお届けします。</p><div class="mx-auto mt-8 max-w-sm rounded-3xl border border-stone-200 bg-white p-6"><p class="text-sm font-bold text-slate-500">注文ID</p><p class="mt-1 text-3xl font-black">#{{ $order->id }}</p><p class="mt-4 border-t border-stone-200 pt-4 text-2xl font-black">¥{{ number_format($order->total_price) }}</p></div><a href="{{ route('products.index') }}" class="mt-8 inline-flex rounded-full bg-slate-950 px-7 py-4 font-black text-white hover:bg-amber-600">買い物を続ける</a></section>
@endsection
