@extends('layouts.base')

@section('content')
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow-lg p-8 flex flex-col items-center">
        <div class="w-full flex flex-col items-center mb-6">
            <img src="{{ $product->image }}" class="w-64 h-64 object-contain border border-gray-200 rounded-lg shadow mb-4 bg-gray-50" alt="{{ $product->name }}">
            <h2 class="text-3xl font-bold mb-2 text-gray-800">{{ $product->name }}</h2>
            <div class="text-xl font-semibold text-primary mb-2">{{ number_format($product->price) }}円</div>
        </div>
        <form action="{{ route('cart.store') }}" method="post" class="w-full">
            @csrf
            @error('quantity')
                <p class="input-error mb-2 px-2 py-1 rounded text-center">{{ $message }}</p>
            @enderror
            <input type="hidden" name="productId" value="{{ $product->id }}">
            <div class="flex flex-col items-center mb-6">
                <label for="quantity" class="text-gray-700 font-medium mb-2">数量</label>
                <input type="number" id="quantity" name="quantity" class="border rounded-lg px-3 py-2 w-24 text-center @error('quantity') input-error @enderror" min="1" value="1">
            </div>
            <div class="flex justify-center">
                <button type="button" onclick="window.location.href='{{ route('register') }}'" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-full shadow transition-colors duration-200 cursor-pointer text-lg">
                    カートに入れる
                </button>
            </div>
        </form>
    </div>
@endsection
