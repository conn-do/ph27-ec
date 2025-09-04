@extends('layouts.base')


@section('content')
    <h2>
        {{ $product->name }}
        <div>
            <img src="{{ $product->image }}" alt="{{ $product->name }}" style="max-width: 300px; max-height: 300px;">
        </div>
        <div>
            {{ $product->price }} 円
        </div>
    </h2>

    <form action="{{ route('cart.store') }}" method="post">
        @csrf
        <div class="gap-1">
            @error('quantity')
                <p class="error-msg mx-2">
                    {{ $message }}
                </p>
            @enderror
            <input type="hidden" name="productId" value="{{ $product->id }}">
            <input type="number" class="focus:outline-2 mx-2 " name="quantity" value="{{ old('quantity') }}">
            <span class="span">個</span> <br>
            <input type="submit" class="submit-btn mx-2" value="カートに入れる">
        </div>
    </form>
@endsection
