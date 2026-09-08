@extends('layouts.base')

@section('title', $product->name)

@section('content')
    <div class="max-w-5xl mx-auto py-8">

        <!-- Navigation Breadcrumb -->
        <div class="mb-8">
            <a href="/"
                class="text-xs font-semibold uppercase tracking-widest text-neutral-400 hover:text-black transition">
                &larr; ALL PRODUCTS
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 bg-white border border-neutral-200 p-8">

            <!-- Product Image -->
            <div class="w-full aspect-square bg-neutral-100 overflow-hidden relative">
                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            </div>

            <!-- Product Information & Actions -->
            <div class="flex flex-col justify-between">
                <div>
                    <!-- Category Badge -->
                    @if (isset($product->category))
                        <div class="mb-3">
                            <a href="/categories/{{ $product->category->slug }}"
                                class="text-[10px] font-semibold uppercase tracking-widest px-2.5 py-1 bg-neutral-100 border border-neutral-200 text-neutral-600 hover:bg-black hover:text-white transition">
                                {{ $product->category->name }}
                            </a>
                        </div>
                    @endif

                    <!-- Product Name -->
                    <h1 class="text-2xl font-light text-neutral-900 tracking-tight uppercase mb-2">
                        {{ $product->name }}
                    </h1>

                    <!-- Price -->
                    <p class="text-xl font-medium text-neutral-900 mb-6">
                        ¥{{ number_format($product->price) }} <span class="text-xs text-neutral-400 font-normal">（税込）</span>
                    </p>

                    <!-- Stock Status -->
                    <div class="mb-6">
                        @if ($product->stock <= 0)
                            <span
                                class="inline-block bg-red-100 text-red-600 text-xs font-semibold px-3 py-1 uppercase tracking-wider">
                                売り切れ
                            </span>
                        @elseif ($product->stock <= 5)
                            <span
                                class="inline-block bg-amber-100 text-amber-700 text-xs font-semibold px-3 py-1 uppercase tracking-wider">
                                残りわずか（在庫: {{ $product->stock }}）
                            </span>
                        @else
                            <span
                                class="inline-block bg-emerald-50 text-emerald-700 text-xs font-semibold px-3 py-1 uppercase tracking-wider">
                                在庫あり
                            </span>
                        @endif
                    </div>

                    <!-- Description -->
                    <p class="text-xs text-neutral-600 leading-relaxed mb-8 border-t border-neutral-100 pt-6">
                        {{ $product->description }}
                    </p>
                </div>

                <!-- Form & Errors Section -->
                <div>
                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border-l-2 border-red-500 p-3 space-y-1">
                            @foreach ($errors->all() as $error)
                                <p class="text-xs text-red-600 font-medium">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <!-- Add to Cart Form -->
                    <form action="/cart" method="POST" class="space-y-4">
                        @csrf

                        <div class="flex items-center gap-4">
                            <label for="quantity" class="text-xs font-semibold uppercase tracking-wider text-neutral-700">
                                個数:
                            </label>
                            <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}"
                                min="1" max="{{ $product->stock }}"
                                class="w-20 px-3 py-2 border @error('quantity') border-red-500 @else border-neutral-300 @enderror text-xs focus:outline-none focus:border-black bg-white text-center">
                        </div>

                        <input type="hidden" name="productId" value="{{ $product->id }}">

                        <button type="submit" @if ($product->stock <= 0) disabled @endif
                            class="w-full bg-black text-white py-3.5 text-xs font-semibold uppercase tracking-widest hover:bg-neutral-800 transition disabled:bg-neutral-300 disabled:cursor-not-allowed cursor-pointer">
                            @if ($product->stock <= 0)
                                SOLDOUT
                            @else
                                カートに入れる
                            @endif
                        </button>
                    </form>

                    <!-- Dynamic Favorite Toggle Button -->
                    @auth
                        <form action="{{ route('favorites.toggle_item', $product->id) }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit"
                                class="w-full border border-neutral-300 bg-white text-neutral-800 py-3 text-xs font-semibold uppercase tracking-widest hover:bg-neutral-50 transition cursor-pointer">
                                @if (auth()->user()->favorites()->where('product_id', $product->id)->exists())
                                    ♥ お気に入りから外す
                                @else
                                    ♡ お気に入りに追加
                                @endif
                            </button>
                        </form>
                    @else
                        <a href="/login"
                            class="mt-3 block w-full text-center border border-neutral-200 bg-neutral-50 text-neutral-400 py-3 text-xs font-semibold uppercase tracking-widest hover:bg-neutral-100 transition">
                            ♡ ログインしてお気に入りに追加
                        </a>
                    @endauth
                </div>

            </div>

        </div>
    </div>
@endsection
