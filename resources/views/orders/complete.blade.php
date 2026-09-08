@extends('layouts.base')

@section('title', 'ご注文完了')

@section('content')
    <div class="max-w-2xl mx-auto py-12">
        <div class="bg-white border border-neutral-200 p-8 md:p-12 text-center shadow-sm">

            <!-- Confirmation Icon & Badge -->
            <div class="w-12 h-12 bg-neutral-900 text-white rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <span class="text-xs font-semibold uppercase tracking-widest text-neutral-400">Order Confirmed</span>
            <h1 class="text-2xl md:text-3xl font-light tracking-tight text-neutral-900 mt-1 mb-4 uppercase">
                ご注文ありがとうございました
            </h1>

            <!-- Flash Message -->
            @if (session('message'))
                <div
                    class="mb-6 bg-neutral-50 border border-neutral-200 text-xs text-neutral-600 px-4 py-3 tracking-wide max-w-md mx-auto">
                    {{ session('message') }}
                </div>
            @endif

            <p class="text-xs text-neutral-500 leading-relaxed mb-8 max-w-md mx-auto">
                ご注文の受付が完了いたしました。内容の詳細は下記または注文履歴よりご確認いただけます。
            </p>

            <!-- Order ID Card -->
            @if ($order)
                <div class="bg-neutral-50 border border-neutral-200 p-4 mb-8 max-w-xs mx-auto">
                    <span class="text-[10px] font-semibold uppercase tracking-widest text-neutral-400 block mb-1">
                        注文番号
                    </span>
                    <span class="text-lg font-mono font-medium text-neutral-900">
                        #{{ sprintf('%06d', $order->id) }}
                    </span>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4 border-t border-neutral-100">
                <a href="/"
                    class="bg-black text-white px-8 py-3 text-xs font-semibold uppercase tracking-widest hover:bg-neutral-800 transition">
                    トップページへ戻る
                </a>

                @if ($order)
                    <a href="/orders/{{ $order->id }}"
                        class="border border-neutral-300 bg-white text-neutral-800 px-8 py-3 text-xs font-semibold uppercase tracking-widest hover:bg-black hover:text-white hover:border-black transition">
                        注文詳細を見る
                    </a>
                @endif
            </div>

        </div>
    </div>
@endsection
