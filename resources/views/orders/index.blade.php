@extends('layouts.base')

@section('title', '注文履歴')

@section('content')
    <div class="max-w-4xl mx-auto py-8">

        <!-- Page Header -->
        <div class="mb-10 border-b border-neutral-200 pb-6 flex items-end justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-widest text-neutral-400">Account History</span>
                <h1 class="text-3xl font-light tracking-tight text-neutral-900 mt-1 uppercase">ORDER HISTORY</h1>
            </div>
            <span class="text-xs font-mono text-neutral-400">
                {{ count($orders) }} orders
            </span>
        </div>

        @if ($orders->isEmpty())
            <!-- Empty State -->
            <div class="bg-white border border-neutral-200 py-16 text-center">
                <p class="text-sm text-neutral-500 mb-6">注文履歴はありません。</p>
                <a href="/"
                    class="inline-block bg-black text-white px-8 py-3 text-xs font-semibold uppercase tracking-widest hover:bg-neutral-800 transition">
                    お買い物を始める
                </a>
            </div>
        @else
            <!-- Orders Table -->
            <div class="bg-white border border-neutral-200 overflow-hidden shadow-sm">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr
                            class="border-b border-neutral-200 bg-neutral-50 text-neutral-400 font-semibold uppercase tracking-wider">
                            <th class="p-4">注文番号</th>
                            <th class="p-4">合計金額</th>
                            <th class="p-4 text-right">詳細</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200">
                        @foreach ($orders as $order)
                            <tr class="hover:bg-neutral-50/50 transition">
                                <td class="p-4 font-mono text-neutral-900 font-medium">
                                    #{{ sprintf('%06d', $order->id) }}
                                </td>
                                <td class="p-4 font-semibold text-neutral-900">
                                    ¥{{ number_format($order->total_price) }}
                                </td>
                                <td class="p-4 text-right">
                                    <a href="/orders/{{ $order->id }}"
                                        class="inline-block bg-black text-white px-4 py-2 text-[10px] font-semibold uppercase tracking-widest hover:bg-neutral-800 transition">
                                        詳細を見る
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
@endsection
