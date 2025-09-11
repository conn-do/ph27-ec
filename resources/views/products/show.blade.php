@extends('layouts.base')

@section('content')
    <div class="max-w-2xl mx-auto p-6">
        <a href="{{ route('products') }}">
            <button class="mb-6 bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg transition-colors">
                ← 商品一覧へ戻る
            </button>
        </a>

        <div class="flex gap-6">
            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="object-cover rounded-lg shadow-md mb-6">

            <div class="flex flex-col items-center">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->name }}</h2>
                <div class="text-2xl font-semibold text-green-600 mb-6">
                    {{ $product->price }}円
                </div>
            </div>
        </div>
 
        <form action="{{ route('cart.store') }}" method="post" class="bg-gray-50 p-6 rounded-lg">
            @csrf
            @error('quantity')
                <p class="text-red-500 text-sm mb-4 bg-red-50 p-3 rounded border border-red-200">{{ $message }}</p>
            @enderror

            <input type="hidden" name="productId" value="{{ $product->id }}">

            <div class="flex items-center mb-4">
                <input type="number" name="quantity"
                    class="w-20 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('quantity') border-red-500 @enderror">
                <span class="ml-2 text-gray-700">個</span>
            </div>

            <input type="submit" value="カートに入れる"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg cursor-pointer transition duration-200">
        </form>
    </div>
@endsection
