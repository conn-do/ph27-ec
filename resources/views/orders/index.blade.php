@extends('layouts.base')

@section('title', '注文履歴')

@section('content')
    <h1 class="text-2xl font-light my-14">注文履歴</h1>

    @if (session('message'))
        <div class="border border-navy-200 rounded-2xl text-sm px-6 py-4 mb-8">{!! session('message') !!}</div>
    @endif

    @if (count($orders) > 0)
        <table class="w-full border-collapse mb-20">
            <tbody>
                @foreach ($orders as $order)
                    <tr class="border-b border-navy-200">
                        <td class="py-5 text-sm text-navy-500">#{{ $order->id }}</td>
                        <td class="py-5 text-sm">¥{{ number_format($order->total_price) }}</td>
                        <td class="py-5 text-sm">
                            @if ($order->is_canceled)
                                <span class="text-navy-500">キャンセル済み</span>
                            @else
                                <span class="text-navy-950">注文済み</span>
                            @endif
                        </td>
                        <td class="py-5 text-right">
                            <a href="/orders/{{ $order->id }}" class="text-xs border border-navy-300 rounded-full px-4 py-1.5 text-navy-950 hover:bg-navy-100 transition whitespace-nowrap">詳細</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-sm text-navy-500 mb-20">まだ注文がありません。</p>
    @endif
@endsection