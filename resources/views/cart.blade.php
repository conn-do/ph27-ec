@extends('layouts.base')

@section('content')
    <h1>カート</h1>
    <ul>
        @foreach ($cart as $item)
            <div>
                {{ $item['product']->name }}
                {{ $item['quantity'] }}個
                {{ $item['product']->price }} 円
            </div>
        @endforeach
    </ul>
    <form action="{{ route('cart.destroy') }}" method="post">
        @csrf
        @method('DELETE')
        <input type="submit" value="カートを空にする">
    </form>
    
@endsection