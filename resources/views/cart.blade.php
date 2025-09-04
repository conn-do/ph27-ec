@extends('layouts.base')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">🛒 ショッピングカート</h1>

        @if (count($items) > 0)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="divide-y divide-gray-200">
                    @foreach ($items as $item)
                        <div class="p-6 flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <img src="{{ asset($item['product']->image) }}" alt="{{ $item['product']->name }}"
                                    class="w-20 h-20 object-cover rounded-lg">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $item['product']->name }}</h3>
                                <p class="text-sm text-gray-600">単価: {{ number_format($item['product']->price) }}円</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-lg font-semibold text-gray-800">{{ $item['quantity'] }}個</span>
                                <span
                                    class="text-xl font-bold text-green-600">{{ number_format($item['product']->price * $item['quantity']) }}円</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- 合計金額 -->
                <div class="bg-gray-50 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-semibold text-gray-800">合計金額</span>
                        <span class="text-2xl font-bold text-green-600">{{ number_format($totalPrice) }}円</span>
                    </div>
                </div>
            </div>

            <!-- アクションボタン -->
            <div class="mt-8 flex flex-col sm:flex-row gap-4">
                <form action="{{ route('cart.destroy') }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full bg-red-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-red-700 transition-colors duration-300">
                        カートを空にする
                    </button>
                </form>

                <form action="{{ route('order') }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit"
                        class="w-full bg-green-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-green-700 transition-colors duration-300">
                        購入する
                    </button>
                </form>
            </div>
        @else
            <!-- 空のカート -->
            <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01">
                    </path>
                </svg>
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">カートが空です</h2>
                <p class="text-gray-600 mb-6">美味しい野菜をカートに追加してみましょう</p>
                <a href="{{ route('products.index') }}"
                    class="inline-block bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors duration-300">
                    商品一覧を見る
                </a>
            </div>
        @endif
    </div>
@endsection
