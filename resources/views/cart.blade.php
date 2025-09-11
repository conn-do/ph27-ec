@extends('layouts.base')

@section('content')
    <div class="container mx-auto px-4">
        <a href="{{ route('products') }}">
            <button class="mb-6 bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg transition-colors">
                ← 商品一覧へ戻る
            </button>
        </a>
        <h2 class="text-3xl font-bold text-gray-800 mb-6">カート</h2>

        <div class="space-y-4 mb-6">
            @foreach ($items as $item)
                <div class="flex items-center bg-white p-4 rounded-lg shadow-md border">
                    <img src="{{ $item['product']->image }}" alt="{{ $item['product']->name }}"
                        class="w-20 h-20 object-cover rounded-lg shadow-md mr-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $item['product']->name }}</h3>
                        <p class="text-gray-600">{{ $item['product']->price }} 円</p>
                        <p class="text-gray-600">{{ $item['quantity'] }} 個</p>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($totalPrice > 0)
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-6">
                <p class="text-xl font-bold text-blue-800">合計金額: {{ $totalPrice }}円</p>
            </div>
        @endif

        <div class="flex gap-4">
            <form action="{{ route('cart.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="submit" value="カートを空にする"
                    class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-medium transition-colors cursor-pointer">
            </form>

            <form action="{{ route('order') }}" method="POST">
                @csrf
                <input type="submit" value="購入する"
                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium transition-colors cursor-pointer">
            </form>
        </div>
    </div>
@endsection
