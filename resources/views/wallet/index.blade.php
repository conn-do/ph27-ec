@extends('layouts.base')

@section('title', 'おこづかい帳')

@section('content')
    <x-page-heading emoji="👛" color="bg-pop-yellow">
        おこづかい帳
        <x-slot:description>おかねが へった じゅんに ならんでいるよ。</x-slot:description>
    </x-page-heading>

    <div class="mb-8 rounded-3xl border-4 border-ink bg-pop-cyan p-6 text-center text-white shadow-block-lg">
        <p class="text-sm font-black text-white/80">のこり</p>
        <p class="calc-number mt-1 text-5xl">
            {{ number_format($user->allowance_balance) }}<span class="text-2xl">えん</span>
        </p>
    </div>

    @if ($transactions->isEmpty())
        <x-empty-state emoji="👛" :action-label="'おみせに いく'" :action-href="route('home')">
            まだ おかねを つかって いないよ。
        </x-empty-state>
    @else
        <ul class="space-y-3">
            @foreach ($transactions as $transaction)
                <li class="flex flex-wrap items-center justify-between gap-4 rounded-3xl border-4 border-ink bg-white p-4 shadow-block-sm">
                    <div>
                        <p class="font-black">
                            @if ($transaction->reason === \App\Models\AllowanceTransaction::REASON_SHOPPING)
                                🛍 おかいもの
                            @elseif ($transaction->reason === \App\Models\AllowanceTransaction::REASON_ALLOWANCE)
                                🎁 おこづかい を もらった
                            @else
                                ↩ はらいもどし
                            @endif
                        </p>
                        <p class="text-xs font-bold text-ink/60">
                            {{ $transaction->created_at->format('Y年n月j日 H:i') }}
                            @if ($transaction->order)
                                ／ <a href="{{ route('orders.show', $transaction->order) }}"
                                    class="underline decoration-2 underline-offset-2">{{ $transaction->order->order_number }}</a>
                            @endif
                        </p>
                    </div>

                    <div class="text-right">
                        <p class="calc-number text-2xl {{ $transaction->amount < 0 ? 'text-pop-red' : 'text-pop-green' }}">
                            {{ $transaction->amount > 0 ? '+' : '' }}{{ number_format($transaction->amount) }}えん
                        </p>
                        <p class="text-xs font-bold text-ink/60">
                            のこり {{ number_format($transaction->balance_after) }}えん
                        </p>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-8">
            {{ $transactions->links() }}
        </div>
    @endif
@endsection
