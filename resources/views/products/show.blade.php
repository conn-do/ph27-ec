@extends('layouts.base')

@section('content')
    <div class="product-detail-container">
        <div class="product-detail-content">
            <div class="product-image-section">
                <img src="{{ $product->image }}" alt="{{ $product->name }}">
            </div>
            <div class="product-info-section">
                <h2>{{ $product->name }}</h2>
                <p class="product-price">{{ $product->price }}円</p>
                <form action="{{ route('cart.store') }}" method="post" class="cart-form">
                    @csrf
                    @error('quantity')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                    <input type="hidden" name="productId" value="{{ $product->id }}">
                    <div class="quantity-input">
                        <label for="quantity">数量:</label>
                        <input type="number" name="quantity" id="quantity"
                            class="@error('quantity') input-error @enderror" min="1" value="1">
                        <span>個</span>
                    </div>
                    <input type="submit" value="カートに入れる" class="add-to-cart-btn">
                </form>
            </div>
        </div>
    </div>
@endsection
