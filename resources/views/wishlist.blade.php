@extends('layouts.base')
@section('title', 'お気に入り')
@section('content')
    <section class="mx-auto max-w-7xl px-5 py-14 lg:px-8">
        <h1 class="text-4xl font-black">お気に入り</h1>
        <p class="mt-4 text-slate-600">気になる商品を保存して、あとでゆっくり選べます。このブラウザーのセッション中に保存されます。</p>
        <div class="mt-8 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($products as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-stone-300 bg-white p-10 text-center"><p>お気に入りはまだありません。</p><a href="{{ route('products.index') }}" class="mt-4 inline-block font-bold text-amber-700 underline">商品を探す →</a></div>
            @endforelse
        </div>
        <div class="mt-8">{{ $products->links() }}</div>
    </section>
@endsection
