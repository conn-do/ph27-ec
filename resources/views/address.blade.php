@extends('layouts.base')  
  
@section('title', '配送先住所')  
  
@section('content')  
    <h2>配送先住所</h2> 
 
    @if (session('message')) 
        <p>{{ session('message') }}</p> 
    @endif 
  
    @if ($address)  
        <p>郵便番号：{{ $address->postal_code }}</p>  
        <p>住所：{{ $address->address }}</p>  
  
        <a href="/address/edit">編集</a>  
    @else  
        <p>配送先住所は登録されていません。</p>  
  
        <form action="/address" method="POST">  
            @csrf  
  
            <label>  
                郵便番号  
                <input type="text" name="postal_code">  
            </label>  
  
            <label>  
                住所  
                <input type="text" name="address">  
            </label>  
  
            <input type="submit" value="保存">  
        </form>  
    @endif  
@endsection