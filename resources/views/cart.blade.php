@extends('layouts.base')

@section('title', 'カート')

@section('content')
    <div class="max-w-4xl mx-auto py-8">

        <!-- Page Title -->
        <div class="mb-10 border-b border-neutral-200 pb-6 flex items-end justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-widest text-neutral-400">Shopping Bag</span>
                <h1 class="text-3xl font-light tracking-tight text-neutral-900 mt-1">YOUR CART</h1>
            </div>

            @unless (empty($items))
                <a href="/cart/clear"
                    class="text-xs text-neutral-400 hover:text-red-600 transition uppercase tracking-wider font-semibold">
                    カートを空にする
                </a>
            @endunless
        </div>

        <!-- Flash Message -->
        @if (session('message'))
            <div class="mb-8 bg-neutral-900 text-white text-xs px-4 py-3 tracking-wide">
                {{ session('message') }}
            </div>
        @endif

        @empty($items)
            <!-- Empty State -->
            <div class="bg-white border border-neutral-200 py-16 text-center">
                <p class="text-sm text-neutral-500 mb-6">カートに商品がありません。</p>
                <a href="/"
                    class="inline-block bg-black text-white px-8 py-3 text-xs font-semibold uppercase tracking-widest hover:bg-neutral-800 transition">
                    商品一覧へ戻る
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

                <!-- Items Table -->
                <div class="lg:col-span-2">
                    <div class="bg-white border border-neutral-200 overflow-hidden">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr
                                    class="border-b border-neutral-200 bg-neutral-50 text-neutral-400 font-semibold uppercase tracking-wider">
                                    <th class="p-4">商品名</th>
                                    <th class="p-4 text-center">数量</th>
                                    <th class="p-4 text-right">小計</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200">
                                @foreach ($items as $item)
                                    <tr>
                                        <td class="p-4 font-semibold text-neutral-900">
                                            <a href="/products/{{ $item['product']->id }}" class="hover:underline">
                                                {{ $item['product']->name }}
                                            </a>
                                            <div class="text-neutral-400 font-normal mt-0.5">
                                                ¥{{ number_format($item['product']->price) }}
                                            </div>
                                        </td>
                                        <td class="p-4 text-center text-neutral-600 font-medium">
                                            {{ $item['quantity'] }}個
                                        </td>
                                        <td class="p-4 text-right font-medium text-neutral-900">
                                            ¥{{ number_format($item['product']->price * $item['quantity']) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="bg-white border border-neutral-200 p-6 h-fit space-y-6">
                    <h2
                        class="text-xs font-semibold uppercase tracking-widest text-neutral-400 pb-3 border-b border-neutral-200">
                        Order Summary
                    </h2>

                    <div class="flex justify-between items-baseline">
                        <span class="text-xs font-semibold uppercase tracking-wider text-neutral-600">合計金額</span>
                        <span class="text-2xl font-light text-neutral-900 tracking-tight">
                            ¥{{ number_format($totalPrice) }}
                        </span>
                    </div>

                    <form action="/orders" method="post">
                        @csrf
                        <button type="submit"
                            class="w-full bg-black text-white py-3.5 text-xs font-semibold uppercase tracking-widest hover:bg-neutral-800 transition cursor-pointer">
                            購入する
                        </button>
                    </form>

                    <a href="/"
                        class="block text-center text-xs text-neutral-400 hover:text-black uppercase tracking-wider transition">
                        &larr; お買い物を続ける
                    </a>
                </div>

            </div>
        @endempty

    </div>
@endsection
