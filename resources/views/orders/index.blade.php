@extends('layouts.base')

@section('title', '注文履歴')

@section('content')
    <h1 class="mb-6 text-xl font-bold text-stone-900">注文履歴</h1>

    @if ($orders->isEmpty())
        <p class="text-sm text-stone-500">注文履歴がありません。</p>
    @else
        <div class="overflow-hidden rounded-xl border border-stone-200 bg-white">
            <table class="w-full text-sm">
                <thead class="border-b border-stone-200 bg-stone-50 text-left text-xs text-stone-500">
                    <tr>
                        <th class="px-4 py-3 font-medium">注文ID</th>
                        <th class="px-4 py-3 font-medium">金額</th>
                        <th class="px-4 py-3 font-medium">ステータス</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach ($orders as $order)
                        <tr>
                            <td class="px-4 py-3 font-medium text-stone-800">{{ $order->id }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ number_format($order->total_price) }}円</td>
                            <td class="px-4 py-3">
                                <span class="inline-block rounded-full bg-stone-100 px-3 py-1 text-xs font-medium text-stone-600">
                                    {{ $order->status->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="/orders/{{ $order->id }}" class="text-xs font-medium text-amber-700 hover:underline">
                                    詳細
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
