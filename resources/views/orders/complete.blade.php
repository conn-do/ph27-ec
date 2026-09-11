@extends('layouts.base')

@section('title', 'おかいものが できました')

@section('content')
    <div class="mb-8 rounded-3xl border-4 border-ink bg-pop-green px-6 py-8 text-center text-white shadow-block-lg animate-pop-in">
        <p class="text-6xl" aria-hidden="true">🎉</p>
        <h1 class="mt-3 text-3xl font-black">おかいもの できました！</h1>
        <p class="mt-2 text-base font-bold">レシートを みてみよう</p>
    </div>

    {{-- レシート --}}
    <div class="mx-auto max-w-xl rounded-3xl border-4 border-ink bg-white p-6 shadow-block">
        <div class="mb-4 border-b-4 border-dashed border-ink/20 pb-4 text-center">
            <p class="text-xl font-black">ワクワクぶんぐや</p>
            <p class="mt-1 text-sm font-bold text-ink/60">
                レシートばんごう {{ $order->order_number }}<br>
                {{ $order->created_at->format('Y年n月j日 H:i') }}
            </p>
        </div>

        {{-- かった もの --}}
        <ul class="space-y-3">
            @foreach ($order->details as $detail)
                <li>
                    <div class="flex items-baseline justify-between gap-2">
                        <span class="font-black">{{ $detail->product_name }}</span>
                        <span class="calc-number text-lg">{{ number_format($detail->subtotal) }}えん</span>
                    </div>
                    <p class="text-xs font-bold text-ink/60">
                        {{ number_format($detail->unit_price) }}えん × {{ $detail->quantity }}こ
                        ／ ぜい{{ $detail->tax_rate }}% は {{ number_format($detail->tax_amount) }}えん
                    </p>
                </li>
            @endforeach
        </ul>

        {{-- ごうけい --}}
        <dl class="mt-5 space-y-2 border-t-4 border-dashed border-ink/20 pt-4">
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

            <div class="flex items-baseline justify-between gap-2 border-t-4 border-ink pt-3">
                <dt class="text-base font-black">おかいけい</dt>
                <dd class="calc-number text-3xl text-pop-red">{{ number_format($order->total_price) }}えん</dd>
            </div>
        </dl>

        {{-- おつりの けいさん --}}
        <div class="mt-6 rounded-2xl border-4 border-ink bg-paper p-4">
            <p class="mb-3 text-sm font-black text-ink/60">おつりの けいさん</p>

            <x-calc-formula :parts="[
                number_format($order->paid_amount),
                '−',
                number_format($order->total_price),
            ]" :answer="number_format($order->change_amount)" highlight />

            @if ($changeBreakdown === [])
                <p class="mt-3 text-base font-black text-pop-green">ぴったり だったよ！ すごい！ 💯</p>
            @else
                <p class="mt-4 mb-2 text-sm font-black text-ink/60">おつりの うちわけ</p>
                <ul class="flex flex-wrap gap-2">
                    @foreach ($changeBreakdown as $money)
                        <li class="rounded-xl border-4 border-ink bg-pop-yellow px-3 py-1 text-base font-black">
                            {{ $money['label'] }} × {{ $money['count'] }}まい
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- おこづかい --}}
        <div class="mt-4 rounded-2xl border-4 border-ink bg-pop-cyan p-4 text-white">
            <div class="flex items-baseline justify-between gap-2">
                <span class="text-sm font-black">のこりの おこづかい</span>
                <span class="calc-number text-2xl">{{ number_format(auth()->user()->allowance_balance) }}えん</span>
            </div>
        </div>
    </div>

    <div class="mt-8 flex flex-wrap justify-center gap-3">
        <x-big-button :href="route('home')" color="bg-pop-blue">もっと みる</x-big-button>
        <x-big-button :href="route('orders.index')" color="bg-white" text="text-ink">かいものの きろく</x-big-button>
    </div>
@endsection
