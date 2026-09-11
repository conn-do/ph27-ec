@extends('layouts.base')

@section('title', 'カート')

@section('content')
    <h1 class="text-2xl font-light my-14">カート</h1>

    @if (session('message'))
        <div class="border border-navy-200 rounded-2xl text-sm px-6 py-4 mb-8">{!! session('message') !!}</div>
    @endif

    @if (count($items) > 0)
        <table class="w-full border-collapse mb-10">
            <tbody>
                @foreach ($items as $item)
                    <tr class="border-b border-navy-200">
                        <td class="py-5 text-sm">{{ $item['product']->name }}</td>
                        <td class="py-5 text-sm text-navy-700">¥{{ number_format($item['product']->price) }}</td>
                        <td class="py-5">
                            <form action="/cart/update" method="post" class="flex items-center gap-3">
                                @csrf
                                <input type="hidden" name="productId" value="{{ $item['product']->id }}">
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="10"
                                    class="w-16 border border-navy-300 rounded-full px-3 py-1.5 text-sm text-center">
                                <button type="submit" class="text-xs border border-navy-300 rounded-full px-4 py-1.5 text-navy-950 hover:bg-navy-100 transition whitespace-nowrap">変更</button>
                            </form>
                        </td>
                        <td class="py-5">
                            <a href="/cart/remove/{{ $item['product']->id }}" class="text-xs border border-navy-300 rounded-full px-4 py-1.5 text-navy-500 hover:bg-red-50 hover:text-red-600 hover:border-red-300 transition whitespace-nowrap">削除</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <form action="/orders" method="post" class="mb-6">
            <button type="submit" class="w-full bg-navy-950 text-white text-sm font-medium rounded-full py-4 hover:bg-navy-700 transition cursor-pointer">購入する</button>
        </form>
    @else
        <p class="text-sm text-navy-500 mb-10">カートに商品がありません。</p>
    @endif

    <div class="flex items-center justify-between text-sm mb-20">
        <a href="/cart/clear" class="text-xs border border-navy-300 rounded-full px-4 py-1.5 text-navy-500 hover:bg-red-50 hover:text-red-600 hover:border-red-300 transition">カートを空にする</a>
        <div>合計: <span class="font-medium">¥{{ number_format($totalPrice) }}</span></div>
    </div>
@endsection