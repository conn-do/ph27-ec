@extends('layouts.base')

@section('title', 'マイページ')

@section('content')
    <div class="mx-auto max-w-7xl px-5 py-12 lg:px-8">
        <div class="rounded-[2rem] bg-teal-900 p-8 text-white md:flex md:items-center md:justify-between md:p-12">
            <div><p class="text-xs font-black tracking-[0.3em] text-amber-300">MY PAGE</p><h1 class="mt-3 font-serif text-4xl font-black">こんにちは、{{ auth()->user()->name }}さん</h1><p class="mt-3 text-sm text-teal-100">お気に入りと注文を、ここでまとめて確認できます。</p></div>
            <div class="mt-7 flex flex-wrap gap-3 md:mt-0"><a href="{{ route('orders.index') }}" class="rounded-full bg-white px-5 py-3 text-sm font-black text-teal-900">注文履歴</a><a href="{{ route('profile.edit') }}" class="rounded-full border border-teal-600 px-5 py-3 text-sm font-bold hover:bg-teal-800">プロフィール編集</a></div>
        </div>

        <section class="mt-14">
            <div class="mb-7 flex items-end justify-between"><div><p class="text-xs font-black tracking-[0.3em] text-rose-700">FAVORITES</p><h2 class="mt-2 font-serif text-3xl font-black">お気に入り</h2></div><span class="text-sm text-stone-500">{{ $favorites->count() }}件</span></div>
            @if ($favorites->isEmpty())
                <div class="rounded-3xl border border-dashed border-stone-300 bg-white px-6 py-16 text-center text-stone-500">まだお気に入りはありません。</div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($favorites as $favorite)<x-product-card :product="$favorite->product" />@endforeach
                </div>
            @endif
        </section>
    </div>
@endsection
