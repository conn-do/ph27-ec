@extends('layouts.base')


@section('content')
    <h2>
        {{ $product->name }}
        <div>
            {{ $product->price }} 円
        </div>
    </h2>

    <form action="{{ route('cart.store') }}" method="post">
        @csrf
        @error('quantity')
            <p style="font-size: 16px;">
                {{ $message }}
            </p>
        @enderror
        <input type="hidden" name="productId" value="{{ $product->id }}">
        <input type="number" class="number" name="quantity">
        <span class="span">個</span> <br>
        <input type="submit" class="submit-btn" value="カートに入れる">
    </form>
@endsection
