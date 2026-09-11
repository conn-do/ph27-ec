@extends('layouts.base')

@section('title', 'かいものの きろく')

@section('content')
    <x-page-heading emoji="🧾" color="bg-pop-purple">
        かいものの きろく
        <x-slot:description>いままでに かったものを ふりかえろう。</x-slot:description>
    </x-page-heading>

    @if ($orders->isEmpty())
        <x-empty-state emoji="🧾" :action-label="'おみせに いく'" :action-href="route('home')">
            まだ なにも かって いないよ。
        </x-empty-state>
    @else
        <ul class="space-y-4">
            @foreach ($orders as $order)
                <li>
                    <a href="{{ route('orders.show', $order) }}"
                        class="flex flex-wrap items-center justify-between gap-4 rounded-3xl border-4 border-ink bg-white p-5 shadow-block block-press">
                        <div>
                            <p class="text-lg font-black">{{ $order->created_at->format('Y年n月j日') }}</p>
                            <p class="text-sm font-bold text-ink/60">
                                {{ $order->order_number }} ／ {{ $order->details_sum_quantity }}こ かった
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="calc-number text-2xl text-pop-red">{{ number_format($order->total_price) }}えん</p>
                            <p class="text-xs font-bold text-ink/60">おつり {{ number_format($order->change_amount) }}えん</p>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @endif
@endsection
