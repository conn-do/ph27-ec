@extends('layouts.base')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- パンくずリスト -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('products.index') }}" class="hover:text-green-600">商品一覧</a></li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    {{ $product->name }}
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- 商品画像 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-96 object-cover">
            </div>

            <!-- 商品情報 -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>

                <div class="mb-6">
                    <span class="text-4xl font-bold text-green-600">{{ number_format($product->price) }}円</span>
                </div>

                <!-- 商品説明 -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">商品について</h3>
                    <p class="text-gray-600 leading-relaxed">
                        新鮮で美味しい{{ $product->name }}をお届けします。農家直送で品質にこだわった野菜です。
                    </p>
                </div>

                <!-- 購入フォーム -->
                <form action="{{ route('cart.store') }}" method="post" class="space-y-4">
                    @csrf
                    <input type="hidden" name="productId" value="{{ $product->id }}">

                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">数量</label>
                        <input type="number" name="quantity" id="quantity" min="1" value="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('quantity') border-red-500 @enderror">
                        @error('quantity')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-green-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-green-700 transition-colors duration-300 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01">
                            </path>
                        </svg>
                        カートに入れる
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
