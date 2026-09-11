@extends('layouts.base')

@section('title', 'カート')

@section('content')
    <h1 class="mb-6 text-xl font-bold text-stone-900">カート</h1>

    @error('quantity')
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
            {{ $message }}
        </div>
    @enderror

    @if (empty($items))
        <p class="text-sm text-stone-500">カートに商品がありません。</p>
    @else
        <div class="overflow-hidden rounded-xl border border-stone-200 bg-white">
            <table class="w-full text-sm">
                <thead class="border-b border-stone-200 bg-stone-50 text-left text-xs text-stone-500">
                    <tr>
                        <th class="px-4 py-3 font-medium">商品名</th>
                        <th class="px-4 py-3 font-medium">単価</th>
                        <th class="px-4 py-3 font-medium">数量</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach ($items as $item)
                        <tr>
                            <td class="px-4 py-3 font-medium text-stone-800">{{ $item['product']->name }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ number_format($item['product']->price) }}円</td>
                            <td class="px-4 py-3">
                                <form action="/cart/{{ $item['product']->id }}" method="post" class="flex items-center gap-2">
                                    @csrf
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="10"
                                        class="w-16 rounded-lg border border-stone-300 px-2 py-1 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                                    <button type="submit"
                                        class="rounded-lg border border-stone-300 px-3 py-1 text-xs font-medium text-stone-600 transition hover:bg-stone-100">
                                        変更
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="/cart/{{ $item['product']->id }}/remove"
                                    class="text-xs font-medium text-red-600 hover:underline">
                                    削除
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex flex-col items-end gap-3">
            <p class="text-lg font-bold text-stone-900">合計: {{ number_format($totalPrice) }}円</p>
            <div class="flex gap-3">
                <a href="/cart/clear" class="rounded-lg border border-stone-300 px-4 py-2 text-sm text-stone-600 transition hover:bg-stone-100">
                    カートを空にする
                </a>
                <a href="/orders/create"
                    class="rounded-lg bg-amber-600 px-6 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-amber-700">
                    購入する
                </a>
            </div>
        </div>
    @endif
@endsection
