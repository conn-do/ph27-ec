@extends('layouts.base')

@section('content')
<h2 class="text-2xl font-bold">商品一覧</h2>
<ul>
    @foreach ($products as $product)
    <!-- organize products into grid of 3 columns, with each column having a card style -->
    <ul class="grid grid-cols-3 gap-4">
        <li class="">
            <a href="{{ route('products.show', ['id' => $product->id]) }}">{{ $product->name }}</a>
            {{ $product->price }}
        </li>
    </ul>
    @endforeach
</ul>
@endsection