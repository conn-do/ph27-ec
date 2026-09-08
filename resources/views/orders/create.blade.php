@extends('layouts.base')

@section('title', 'ご配送先の入力')

@section('content')
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <article class="error">{{ $error }}</article>
        @endforeach
    @endif

    <h1>ご配送先の入力</h1>

    <form action="/orders" method="post">
        @csrf

        <label>
            お名前
            <input type="text" name="shipping_name" value="{{ old('shipping_name') }}">
        </label>

        <label>
            郵便番号（例: 123-4567）
            <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}">
        </label>

        <label>
            ご住所
            <input type="text" name="shipping_address" value="{{ old('shipping_address') }}">
        </label>

        <label>
            電話番号
            <input type="text" name="shipping_phone" value="{{ old('shipping_phone') }}">
        </label>

        <button type="submit">この内容で注文を確定する</button>
    </form>
@endsection
