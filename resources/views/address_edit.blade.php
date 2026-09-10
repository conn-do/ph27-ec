@extends('layouts.base')

@section('title', '配送先住所の編集')

@section('content')
    <h2>配送先住所の編集</h2>

    <form action="/address" method="POST">
        @csrf

        <label>
            郵便番号
            <input type="text" name="postal_code" value="{{ $address->postal_code }}">
        </label>

        <label>
            住所
            <input type="text" name="address" value="{{ $address->address }}">
        </label>

        <input type="submit" value="保存">
    </form>
@endsection