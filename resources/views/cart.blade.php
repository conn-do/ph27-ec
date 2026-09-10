@extends('layouts.base')
@section('title', 'カート')
@section('content')
    <section class="mx-auto max-w-6xl px-5 py-14 lg:px-8">
        <p class="font-black text-amber-600">YOUR SELECTION</p><h1 class="mt-1 text-5xl font-black tracking-tight">ショッピングカート</h1>
        @if ($items->isEmpty())
            <div class="mt-10 rounded-[2rem] border border-dashed border-stone-300 bg-white p-12 text-center"><div class="text-5xl">🛒</div><h2 class="mt-4 text-2xl font-black">カートは空です</h2><p class="mt-2 text-slate-500">お気に入りの文房具を見つけにいきましょう。</p><a href="{{ route('products.index') }}" class="mt-6 inline-flex rounded-full bg-slate-950 px-6 py-3 font-black text-white hover:bg-amber-600">商品を見る</a></div>
        @else
            <div class="mt-10 grid gap-8 lg:grid-cols-[1fr_22rem] lg:items-start">
                <div class="space-y-4">
                    @foreach ($items as $item)
                        <article class="grid gap-5 rounded-3xl border border-stone-200 bg-white p-4 sm:grid-cols-[8rem_1fr] sm:items-center"><a href="{{ route('products.show', $item['product']) }}"><img src="{{ asset($item['product']->image) }}" alt="{{ $item['product']->name }}" class="aspect-square w-full rounded-2xl object-cover"></a><div><div class="flex items-start justify-between gap-4"><div><h2 class="text-xl font-black">{{ $item['product']->name }}</h2><p class="text-sm text-slate-500">単価 ¥{{ number_format($item['product']->price) }}</p></div><p class="text-xl font-black">¥{{ number_format($item['subtotal']) }}</p></div><div class="mt-4 flex flex-wrap items-center gap-3"><form action="{{ route('cart.update', $item['product']) }}" method="POST" class="flex items-center gap-2">@csrf @method('PATCH')<label for="quantity-{{ $item['product']->id }}" class="text-sm font-bold">数量</label><input id="quantity-{{ $item['product']->id }}" type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="10" class="w-20 rounded-xl border border-stone-300 px-3 py-2"><button class="rounded-xl bg-slate-100 px-4 py-2 text-sm font-bold hover:bg-amber-100">更新</button></form><form action="{{ route('cart.destroy', $item['product']) }}" method="POST">@csrf @method('DELETE')<button class="px-2 py-2 text-sm font-bold text-rose-600 hover:underline">削除</button></form></div>@error('quantity')<p class="mt-2 text-sm font-bold text-rose-600">{{ $message }}</p>@enderror</div></article>
                    @endforeach
                    <form action="{{ route('cart.clear') }}" method="POST">@csrf @method('DELETE')<button class="text-sm font-bold text-slate-500 underline hover:text-rose-600">カートを空にする</button></form>
                </div>
                <aside class="rounded-[2rem] bg-slate-950 p-7 text-white lg:sticky lg:top-28"><p class="font-bold text-slate-400">ご注文内容</p><div class="mt-5 flex items-end justify-between border-b border-slate-700 pb-5"><span>合計</span><strong class="text-4xl">¥{{ number_format($totalPrice) }}</strong></div>@guest<p class="mt-5 text-sm leading-6 text-slate-300">購入にはログインが必要です。</p><a href="{{ route('login') }}" class="mt-4 flex w-full justify-center rounded-full bg-amber-400 px-5 py-4 font-black text-slate-950 hover:bg-amber-300">ログインして購入</a>@else<form action="{{ route('orders.store') }}" method="POST" class="mt-6">@csrf<button class="w-full rounded-full bg-amber-400 px-5 py-4 font-black text-slate-950 hover:bg-amber-300">注文を確定する</button></form>@endguest<p class="mt-4 text-center text-xs text-slate-400">内容を確認してから注文してください</p></aside>
            </div>
        @endif
    </section>
@endsection
