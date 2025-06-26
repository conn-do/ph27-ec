@extends('layouts.base')


@section('content')
    <h2>
        {{ $product->name }}
        <div>
            {{ $product->price }} 円
        </div>
    </h2>
@endsection
