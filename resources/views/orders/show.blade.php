@extends('layouts.base')

@section('title', 'かいものの なかみ')

@section('content')
    <a href="{{ route('orders.index') }}"
        class="mb-4 inline-block text-sm font-black underline decoration-4 decoration-pop-blue underline-offset-4">
        ← きろくに もどる
    </a>

    <x-page-heading emoji="🧾" color="bg-pop-purple">
        {{ $order->created_at->format('Y年n月j日') }} の おかいもの
        <x-slot:description>{{ $order->order_number }}</x-slot:description>
    </x-page-heading>

    {{-- かった もの --}}
    <ul class="space-y-4">
        @foreach ($order->details as $detail)
            <li class="rounded-3xl border-4 border-ink bg-white p-4 shadow-block">
                <div class="flex gap-4">
                    @if ($detail->product)
                        <a href="{{ route('products.show', $detail->product) }}"
                            class="h-20 w-20 shrink-0 overflow-hidden rounded-2xl border-4 border-ink bg-paper-deep">
                            <img src="{{ $detail->product->imageUrl() }}" alt="{{ $detail->product_name }}"
                                class="h-full w-full object-contain p-1">
                        </a>
                    @endif

                    <div class="min-w-0 grow">
                        <p class="text-lg font-black">{{ $detail->product_name }}</p>
                        <p class="mt-1 text-sm font-bold text-ink/60">
                            {{ number_format($detail->unit_price) }}えん × {{ $detail->quantity }}こ
                            = {{ number_format($detail->subtotal) }}えん
                        </p>
                        <p class="text-sm font-bold text-ink/60">
                            しょうひぜい {{ $detail->tax_rate }}% → {{ number_format($detail->tax_amount) }}えん
                        </p>
                    </div>

                    <div class="shrink-0 text-right">
                        <p class="calc-number text-2xl">{{ number_format($detail->totalWithTax()) }}</p>
                        <p class="text-xs font-bold text-ink/60">えん（ぜいこみ）</p>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>

    {{-- ごうけいの けいさん --}}
    <div class="mt-6 rounded-3xl border-4 border-ink bg-white p-6 shadow-block">
        <h2 class="mb-4 text-xl font-black">この ひの けいさん</h2>

        <dl class="space-y-2">
            <div class="flex items-baseline justify-between gap-2">
                <dt class="text-sm font-black">ぜいぬきの ごうけい</dt>
                <dd class="calc-number text-xl">{{ number_format($order->subtotal) }}えん</dd>
            </div>

            @foreach ($order->taxGroups() as $group)
                <div class="flex items-baseline justify-between gap-2">
                    <dt class="text-sm font-black">
                        しょうひぜい {{ $group['rate'] }}%
                        <span class="font-bold text-ink/50">
                            （{{ number_format($group['subtotal']) }} × {{ $group['rate'] }} ÷ 100）
                        </span>
                    </dt>
                    <dd class="calc-number text-xl">{{ number_format($group['tax']) }}えん</dd>
                </div>
            @endforeach

            <div class="flex items-baseline justify-between gap-2 border-t-4 border-dashed border-ink/20 pt-3">
                <dt class="text-base font-black">おかいけい</dt>
                <dd class="calc-number text-3xl text-pop-red">{{ number_format($order->total_price) }}えん</dd>
            </div>
            <div class="flex items-baseline justify-between gap-2">
                <dt class="text-sm font-black">だした おかね</dt>
                <dd class="calc-number text-xl">{{ number_format($order->paid_amount) }}えん</dd>
            </div>
            <div class="flex items-baseline justify-between gap-2">
                <dt class="text-sm font-black">おつり</dt>
                <dd class="calc-number text-xl text-pop-green">{{ number_format($order->change_amount) }}えん</dd>
            </div>
        </dl>
    </div>
@endsection
