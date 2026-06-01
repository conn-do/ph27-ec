@extends('layouts.base')


@section('title', '商品一覧')

@section('content')
    <h2>商品一覧</h2>
    @foreach ($products as $product)
        <ul>
            <li>{{ $product['name'] }}</li>
        </ul>
    @endforeach
@endsection
