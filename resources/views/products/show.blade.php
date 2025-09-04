@extends('layouts.base')

@section('content')
    <h1>商品詳細</h1>
    <h2>{{ $product->name }}</h2>
    <p>{{ $product->price }}円</p>
    <form action="{{ route('cart.store') }}" method="post">
        @csrf
        <input type="hidden" name="productId" value="{{ $product->id }}">
        @if ($errors->has('quantity'))
            <div style="color: red; margin-bottom: 5px;">
                {{ $errors->first('quantity') }}
            </div>
        @endif
        <input type="number" name="quantity" value="{{ old('quantity', 1) }}" class="@error('quantity') is-invalid @enderror">
        <input type="submit" value="カートに入れる">
       

    </form>
    <a href="{{ route('products.index') }}">商品一覧に戻る</a>
    <a href="{{ route('cart.index') }}">カートを見る</a>
@endsection