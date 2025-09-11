@extends('layouts.base')

@section('content')
    <div class="max-w-4xl mx-auto p-6">
        <h2 class="text-3xl font-bold text-red-600 mb-6">🔥 セール中の商品</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            @foreach ($saleProducts as $product)
                <div
                    class="flex flex-col items-center bg-red-50 border border-red-200 rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="h-48 mb-2">
                    <h3 class="font-semibold text-lg mb-2 text-red-800">{{ $product->name }}</h3>
                    <div class="flex items-center">
                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded mr-2">SALE</span>
                        <p class="text-red-600 font-bold text-xl">{{ number_format($product->price) }} 円</p>
                    </div>
                </div>
            @endforeach
        </div>

        <h2 class="text-3xl font-bold text-gray-800 mb-6">商品一覧</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            @foreach ($products as $product)
                <a href="{{ route('products.show', ['id' => $product->id]) }}"
                    class="block hover:text-blue-600 transition-colors">
                    <div
                        class="flex flex-col items-center bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="h-48 mb-2">
                        <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                        <p class="text-green-600 font-bold text-xl">{{ number_format($product->price) }} 円</p>
                    </div>
                </a>
            @endforeach
        </div>

    </div>
@endsection
