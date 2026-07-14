@extends('layouts.base')

@section('title', $category->name)

@section('content')
    <h1>{{ $category->name }}</h1>
    @foreach ($category->products as $product)
        <div>
            <a href="/products/{{ $product->id }}">
                {{ $product->name }}
                <img src="{{ $product->imageUrl() }}">
            </a>
        </div>
    @endforeach
@endsection
