@extends('layouts.base')

@section('title', $product->name)

@section('content')

    <div class="product-detail-page">

        {{-- =========================
         Back
    ========================= --}}

        <div class="product-back">
            <a href="/">
                ← BACK TO CATALOG
            </a>
        </div>


        {{-- =========================
         Product Detail
    ========================= --}}

        <div class="product-detail">

            {{-- =========================
             Image
        ========================= --}}

            <div class="product-detail-image-wrap">

                <div class="product-detail-image">

                    <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">

                </div>

            </div>


            {{-- =========================
             Information
        ========================= --}}

            <div class="product-detail-info">

                <div class="product-meta">

                    <a href="/categories/{{ $product->category->slug }}" class="product-category">
                        {{ $product->category->name }}
                    </a>


                    @if ($product->stock <= 0)
                        <span class="stock-status stock-soldout">
                            SOLD OUT
                        </span>
                    @elseif ($product->stock <= 5)
                        <span class="stock-status stock-low">
                            LOW STOCK
                        </span>
                    @else
                        <span class="stock-status stock-available">
                            IN STOCK
                        </span>
                    @endif

                </div>


                <h1 class="product-detail-title">
                    {{ $product->name }}
                </h1>


                <p class="product-detail-price">
                    ¥{{ number_format($product->price) }}
                </p>


                <div class="product-detail-divider"></div>


                <p class="product-description-label">
                    ABOUT THIS PRODUCT
                </p>


                <p class="product-description">
                    {{ $product->description }}
                </p>


                {{-- =========================
                 Errors
            ========================= --}}

                @if ($errors->any())

                    <div class="product-errors">

                        @foreach ($errors->all() as $error)
                            <p>
                                {{ $error }}
                            </p>
                        @endforeach

                    </div>

                @endif


                {{-- =========================
                 Purchase
            ========================= --}}

                <div class="purchase-box">

                    <form action="/cart" method="POST">

                        @csrf

                        <div class="purchase-row">

                            <label for="quantity" class="purchase-label">
                                Quantity
                            </label>

                            <input type="number" id="quantity" name="quantity" class="quantity-input" min="1"
                                max="10" value="{{ old('quantity', 1) }}">

                        </div>


                        <input type="hidden" name="productId" value="{{ $product->id }}">


                        <button type="submit" class="cart-button" @if ($product->stock <= 0) disabled @endif>
                            @if ($product->stock <= 0)
                                SOLD OUT
                            @else
                                ADD TO CART
                            @endif
                        </button>

                    </form>

                </div>


                <div class="product-number">
                    PRODUCT No.
                    {{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}
                </div>

            </div>

        </div>

    </div>

@endsection
