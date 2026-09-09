@extends('layouts.base')

@section('title', 'マイページ')

@section('content')
    <a href="/orders">注文履歴</a>

    <section>
        <h2>お気に入り一覧</h2>

        @if ($favoriteProducts->isEmpty())
            <p>お気に入りの商品はまだありません。</p>
        @else
            <ul>
                @foreach ($favoriteProducts as $product)
                    <li>
                        <a href="/products/{{ $product->id }}">
                            <img src="{{ $product->imageUrl() }}" width="120" alt="{{ $product->name }}">
                            <div>
                                <p>{{ $product->name }}</p>
                                <p>{{ $product->price }}円</p>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
@endsection
