@extends('layouts.base')

@section('title', $product->name)

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-16 py-16">
        <div class="bg-navy-100 rounded-2xl flex items-center justify-center h-[420px] overflow-hidden">
            <img src="{{ $product->imageUrl() }}" class="max-h-full max-w-full object-contain">
        </div>

        <div class="flex flex-col gap-6">
            <div>
                <a href="/categories/{{ $product->category->slug }}" class="text-xs text-navy-500 hover:text-navy-950">
                    {{ $product->category->name }}
                </a>
                <h1 class="text-2xl font-light mt-2">{{ $product->name }}</h1>
            </div>

            <div class="text-xl font-medium text-navy-950">¥{{ number_format($product->price) }}</div>

            <div>
                @if ($product->stock <= 0)
                    <span class="inline-block text-xs border border-red-200 text-red-600 bg-red-50 rounded-full px-4 py-1.5">売り切れ</span>
                @elseif ($product->stock <= 5)
                    <span class="inline-block text-xs border border-amber-200 text-amber-700 bg-amber-50 rounded-full px-4 py-1.5">残りわずか</span>
                @else
                    <span class="inline-block text-xs border border-navy-200 text-navy-700 bg-navy-100 rounded-full px-4 py-1.5">在庫あり</span>
                @endif
            </div>

            <p class="text-sm text-navy-500 leading-relaxed">{{ $product->description }}</p>

            @if ($errors->any())
                <div class="border border-red-200 bg-red-50 text-red-600 rounded-2xl text-sm px-6 py-4">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if ($product->stock > 0)
                <form action="/cart" method="POST" class="flex items-center gap-4 mt-4">
                    @csrf
                    <label class="flex items-center gap-3 border border-navy-300 rounded-full px-5 py-3">
                        <span class="text-sm text-navy-500">個数</span>
                        <input type="number" name="quantity" min="1" max="10" value="{{ old('quantity', 1) }}"
                            class="Dw-14 border-0 outline-none text-sm text-center bg-transparent @error('quantity') text-red-600 @enderror">
                    </label>
                    <input type="hidden" name="productId" value="{{ $product->id }}">
                    <button type="submit" class="bg-navy-950 text-white text-sm font-medium rounded-full px-8 py-3 hover:bg-navy-700 transition cursor-pointer">
                        カートに入れる
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection