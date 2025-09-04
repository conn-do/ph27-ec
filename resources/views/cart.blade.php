@extends('layouts.base')

@section('content')
    <h1>カート</h1>
    <div>
        @if (session('error'))
            <div class="alert alert-danger" style="color: red;">
                {{ session('error') }}
            </div>
        @endif
        <table class="table table-bordered cart-table">
            <thead>
                <tr>
                    <th>商品名</th>
                    <th>数量</th>
                    <th>価格</th>
                    <th>小計</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart as $item)
                    <tr>
                        <td>{{ $item['product']->name }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ $item['product']->price }}</td>
                        <td>{{ $item['product']->price * $item['quantity'] }}</td>
                        <td>
                            <form action="{{ route('cart.destroy') }}" method="post">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="productId" value="{{ $item['product']->id }}">
                                <input type="submit" value="削除">
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <span>合計金額: {{ array_sum(array_map(function($item) { return $item['product']->price * $item['quantity']; }, $cart)) }}</span>
    </div>
    <form action="{{ route('cart.destroy') }}" method="post">
        @csrf
        @method('DELETE')
        <input type="submit" value="カートを空にする">
    </form>
    <form action="{{ route('order') }}" method="post">
        @csrf
        <input type="submit" value="注文する">
    </form>
    <a href="{{ route('products.index') }}">商品一覧に戻る</a>
    
@endsection