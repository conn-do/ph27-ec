@extends('layouts.base')

@section('title', 'レジ')

@section('content')
    <x-page-heading emoji="🧮" color="bg-pop-green">
        レジで おかいけい
        <x-slot:description>いくらに なるか たしかめて、じぶんで おかねを だしてみよう。</x-slot:description>
    </x-page-heading>

    {{-- ① いくらに なるか --}}
    <section class="mb-8">
        <x-calc-panel :calculation="$calculation" :tax-labels="$taxLabels" />
    </section>

    {{-- ② おかねを だす --}}
    <section data-checkout data-total="{{ $calculation['total'] }}" data-wallet="{{ auth()->user()->allowance_balance }}"
        class="rounded-3xl border-4 border-ink bg-white shadow-block-lg">

        <h2 class="flex flex-wrap items-center justify-between gap-2 rounded-t-2xl border-b-4 border-ink bg-pop-yellow px-5 py-4">
            <span class="flex items-center gap-3 text-xl font-black">
                <span class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-ink bg-white text-base">4</span>
                おかねを だそう
            </span>
            <span class="rounded-full border-2 border-ink bg-white px-3 py-1 text-sm font-black">
                おさいふ {{ number_format(auth()->user()->allowance_balance) }}えん
            </span>
        </h2>

        <div class="px-5 py-6">
            {{-- はらう きんがく --}}
            <div class="mb-6 grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border-4 border-ink bg-paper px-4 py-3 text-center">
                    <p class="text-xs font-black text-ink/60">ねだん</p>
                    <p class="calc-number text-3xl">{{ number_format($calculation['total']) }}</p>
                    <p class="text-xs font-bold text-ink/60">えん</p>
                </div>
                <div class="rounded-2xl border-4 border-ink bg-pop-blue px-4 py-3 text-center text-white">
                    <p class="text-xs font-black text-white/80">だした おかね</p>
                    <p class="calc-number text-3xl" data-paid-display>0</p>
                    <p class="text-xs font-bold text-white/80">えん</p>
                </div>
                <div class="rounded-2xl border-4 border-ink bg-pop-pink px-4 py-3 text-center text-white">
                    <p class="text-xs font-black text-white/80">おつり</p>
                    <p class="calc-number text-3xl" data-change-display>？</p>
                    <p class="text-xs font-bold text-white/80">えん</p>
                </div>
            </div>

            {{-- おつりの しき --}}
            <p class="mb-6 rounded-2xl bg-paper-deep px-4 py-3 text-center text-base font-black">
                だした おかね <span class="text-ink/50">−</span> ねだん <span class="text-ink/50">=</span> おつり
            </p>

            {{-- おかねの ボタン --}}
            <p class="mb-3 text-sm font-black text-ink/60">おかねを タップして だしてね</p>
            <div class="mb-5 flex flex-wrap gap-3">
                @foreach ($denominations as $denomination)
                    <button type="button" data-denomination="{{ $denomination['value'] }}"
                        class="rounded-2xl border-4 border-ink px-4 py-3 text-lg font-black shadow-block-sm block-press {{ $denomination['type'] === 'bill' ? 'bg-pop-green text-white' : 'bg-white' }}">
                        {{ $denomination['type'] === 'bill' ? '💴' : '🪙' }} {{ $denomination['label'] }}
                    </button>
                @endforeach
            </div>

            {{-- だした おかね --}}
            <div class="mb-5 min-h-16 rounded-2xl border-4 border-dashed border-ink/30 bg-paper p-3">
                <p class="mb-2 text-xs font-black text-ink/60">いま だしている おかね</p>
                <ul class="flex flex-wrap gap-2" data-stack></ul>
            </div>

            <div class="mb-6 flex flex-wrap items-center gap-3">
                <button type="button" data-undo
                    class="rounded-2xl border-4 border-ink bg-white px-4 py-2 text-sm font-black shadow-block-sm block-press">
                    ↩ ひとつ もどす
                </button>
                <button type="button" data-reset
                    class="rounded-2xl border-4 border-ink bg-white px-4 py-2 text-sm font-black shadow-block-sm block-press">
                    ぜんぶ もどす
                </button>
                <span data-status-display
                    class="rounded-2xl border-4 border-ink bg-pop-orange px-4 py-2 text-sm font-black text-white"></span>
            </div>

            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <input type="hidden" name="paid_amount" value="0" data-paid-input>
                <button type="submit" data-submit
                    class="w-full rounded-3xl border-4 border-ink bg-pop-red px-6 py-5 text-2xl font-black text-white shadow-block">
                    これで かう！ 🎉
                </button>
            </form>
        </div>
    </section>

    <div class="mt-6">
        <a href="{{ route('cart.index') }}"
            class="text-sm font-black underline decoration-4 decoration-pop-blue underline-offset-4">
            ← カートに もどる
        </a>
    </div>
@endsection
