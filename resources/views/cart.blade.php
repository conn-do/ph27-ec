@extends('layouts.base')

@section('title', 'ショッピングカート')

@section('content')
    <div class="mx-auto max-w-6xl px-5 py-12 lg:px-8">
        <p class="text-xs font-black tracking-[0.3em] text-teal-700">YOUR CART</p>
        <h1 class="mt-3 font-serif text-4xl font-black">ショッピングカート</h1>

        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-bold text-rose-800">{{ $errors->first() }}</div>
        @endif

        @if ($items->isEmpty())
            <div class="mt-10 rounded-[2rem] border border-dashed border-stone-300 bg-white px-6 py-20 text-center">
                <p class="text-5xl">✎</p><h2 class="mt-5 font-serif text-2xl font-bold">カートは空です</h2><p class="mt-2 text-sm text-stone-500">お気に入りの文房具を探しに行きましょう。</p>
                <a href="{{ route('home') }}#products" class="mt-7 inline-flex rounded-full bg-teal-800 px-6 py-3 text-sm font-black text-white">商品を見る</a>
            </div>
        @else
            <div class="mt-10 grid gap-8 lg:grid-cols-[1fr_22rem]">
                <div class="space-y-4">
                    @foreach ($items as $item)
                        <article class="grid gap-5 rounded-3xl border border-stone-200 bg-white p-4 sm:grid-cols-[8rem_1fr]">
                            <img src="{{ $item['product']->imageUrl() }}" alt="{{ $item['product']->name }}" class="aspect-square w-full rounded-2xl object-cover">
                            <div class="flex flex-col justify-between gap-5 py-1">
                                <div><a href="{{ route('products.show', $item['product']) }}" class="font-serif text-xl font-bold hover:text-teal-800">{{ $item['product']->name }}</a><p class="mt-2 font-black">¥{{ number_format($item['product']->price) }}</p></div>
                                <div class="flex flex-wrap items-end justify-between gap-3">
                                    <form action="{{ route('cart.update', $item['product']) }}" method="POST" class="flex items-end gap-2">
                                        @csrf @method('PATCH')
                                        <label class="text-xs font-bold text-stone-500">数量<input type="number" name="quantity" min="1" max="{{ min(10, $item['product']->stock) }}" value="{{ $item['quantity'] }}" class="mt-1 block w-20 rounded-xl border border-stone-300 px-3 py-2 text-stone-900"></label>
                                        <button class="rounded-xl border border-stone-300 px-4 py-2 text-sm font-bold hover:border-teal-700">更新</button>
                                    </form>
                                    <form action="{{ route('cart.destroy', $item['product']) }}" method="POST">@csrf @method('DELETE')<button class="text-sm font-bold text-stone-400 hover:text-rose-700">削除</button></form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                <aside class="h-fit rounded-[2rem] bg-stone-900 p-7 text-white lg:sticky lg:top-28">
                    <p class="text-xs font-black tracking-[0.25em] text-stone-400">ORDER SUMMARY</p>
                    <div class="mt-6 flex items-end justify-between border-b border-stone-700 pb-6"><span>合計</span><strong class="text-3xl">¥{{ number_format($totalPrice) }}</strong></div>
                    @auth
                        <form action="{{ route('orders.store') }}" method="POST" class="mt-6">@csrf<button class="w-full rounded-2xl bg-amber-300 px-5 py-4 font-black text-stone-900 hover:bg-amber-200">注文を確定する</button></form>
                    @else
                        <a href="{{ route('login') }}" class="mt-6 block rounded-2xl bg-amber-300 px-5 py-4 text-center font-black text-stone-900">ログインして購入</a>
                    @endauth
                    <form action="{{ route('cart.clear') }}" method="POST" class="mt-3">@csrf @method('DELETE')<button class="w-full py-2 text-sm text-stone-400 hover:text-white">カートを空にする</button></form>
                </aside>
            </div>
        @endif
    </div>
@endsection
