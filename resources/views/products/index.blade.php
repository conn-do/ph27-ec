@extends('layouts.base')

@section('content')
    <!-- ヒーローセクション -->
    <div class="bg-green-600 rounded-lg p-8 mb-8 text-white">
        <h1 class="text-4xl font-bold mb-4">新鮮な野菜をお届け</h1>
        <p class="text-xl">農家直送の美味しい野菜を全国にお届けします</p>
    </div>

    <!-- 商品一覧 -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">🥕 商品一覧</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($products as $product)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                    <div class="bg-gray-200">
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $product->name }}</h3>
                        <p class="text-2xl font-bold text-green-600 mb-4">{{ number_format($product->price) }}円</p>
                        <a href="{{ route('products.show', ['id' => $product->id]) }}"
                            class="inline-block bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors duration-300">
                            詳細を見る
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- セール商品 -->
    @if ($saleProducts->count() > 0)
        <section>
            <h2 class="text-3xl font-bold text-gray-800 mb-6">🔥 セール中の商品</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($saleProducts as $product)
                    <div
                        class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden border-2 border-red-200">
                        <div class="relative">
                            <div class="bg-gray-200">
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                    class="w-full h-48 object-cover">
                            </div>
                            <div
                                class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-full text-sm font-bold">
                                SALE
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $product->name }}</h3>
                            <p class="text-2xl font-bold text-red-600 mb-4">{{ number_format($product->price) }}円</p>
                            <a href="{{ route('products.show', ['id' => $product->id]) }}"
                                class="inline-block bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors duration-300">
                                詳細を見る
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
@endsection
