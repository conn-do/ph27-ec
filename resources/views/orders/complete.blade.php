@extends('layouts.base')

@section('title', 'ご注文完了')

@section('content')
    <div class="mx-auto max-w-md text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100">
            <svg class="h-7 w-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <h1 class="mt-4 text-xl font-bold text-stone-900">ご注文ありがとうございました</h1>
        <p class="mt-1 text-sm text-stone-500">確認メールをお送りしましたのでご確認ください。</p>

        @if ($order)
            <div class="mt-6 rounded-xl border border-stone-200 bg-white p-5 text-left">
                <p class="text-sm text-stone-500">注文ID</p>
                <p class="text-base font-bold text-stone-900">{{ $order->id }}</p>

                <h2 class="mt-4 mb-2 text-sm font-bold text-stone-900">お届け先</h2>
                <p class="text-sm text-stone-700">{{ $order->shipping_name }} 様</p>
                <p class="text-sm text-stone-500">〒{{ $order->shipping_postal_code }}</p>
                <p class="text-sm text-stone-500">{{ $order->shipping_address }}</p>
                <p class="text-sm text-stone-500">{{ $order->shipping_phone }}</p>
            </div>

            <a href="/orders/{{ $order->id }}"
                class="mt-6 inline-block rounded-lg bg-amber-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-amber-700">
                注文詳細を見る
            </a>
        @endif
    </div>
@endsection
