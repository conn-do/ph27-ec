@extends('layouts.base')

@section('title', '注文詳細 #' . $order->id)

@section('content')
    <div class="max-w-4xl mx-auto py-8">

        <!-- Navigation Breadcrumb -->
        <div class="mb-8">
            <a href="/orders"
                class="text-xs font-semibold uppercase tracking-widest text-neutral-400 hover:text-black transition">
                &larr; BACK TO ORDER HISTORY
            </a>
        </div>

        <!-- Header & Order Summary Card -->
        <div class="bg-white border border-neutral-200 p-8 shadow-sm mb-8">
            <div class="border-b border-neutral-200 pb-6 mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-widest text-neutral-400">Order Summary</span>
                    <h1 class="text-2xl font-light tracking-tight text-neutral-900 mt-1 uppercase font-mono">
                        ORDER #{{ sprintf('%06d', $order->id) }}
                    </h1>
                </div>

                <div class="text-left sm:text-right">
                    <span class="text-xs text-neutral-400 font-mono block">注文日時</span>
                    <span class="text-xs font-medium text-neutral-700">
                        {{ $order->created_at ? $order->created_at->format('Y/m/d H:i') : '-' }}
                    </span>
                </div>
            </div>

            <!-- Total Price Display -->
            <div class="flex justify-between items-baseline bg-neutral-50 p-4 border border-neutral-200">
                <span class="text-xs font-semibold uppercase tracking-wider text-neutral-600">合計金額</span>
                <span class="text-2xl font-light text-neutral-900 tracking-tight">
                    ¥{{ number_format($order->total_price) }}
                </span>
            </div>
        </div>

        <!-- Purchased Items Table -->
        <div class="bg-white border border-neutral-200 overflow-hidden shadow-sm">
            <div class="p-4 border-b border-neutral-200 bg-neutral-50">
                <h2 class="text-xs font-semibold uppercase tracking-widest text-neutral-400">
                    注文商品一覧
                </h2>
            </div>

            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-neutral-200 text-neutral-400 font-semibold uppercase tracking-wider">
                        <th class="p-4">商品名</th>
                        <th class="p-4 text-right">数量</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @foreach ($order->details as $detail)
                        <tr class="hover:bg-neutral-50/50 transition">
                            <td class="p-4 font-semibold text-neutral-900">
                                @if ($detail->product)
                                    <a href="/products/{{ $detail->product->id }}" class="hover:underline">
                                        {{ $detail->product->name }}
                                    </a>
                                @else
                                    <span class="text-neutral-400">削除された商品</span>
                                @endif
                            </td>
                            <td class="p-4 text-right text-neutral-600 font-medium">
                                {{ $detail->quantity }}個
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
