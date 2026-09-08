@extends('layouts.base')

@section('title', $product->name)

@section('content')
<div style="background-color: #13161E; color: #fff; max-width: 800px; margin: 0 auto;">
    <div style="display: flex; gap: 40px; align-items: flex-start;">
        <div style="width: 300px; height: 300px; background-color: #1a1e29; flex-shrink: 0;">
            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; filter: grayscale(100%); display: block;">
        </div>
        
        <div style="flex-grow: 1;">
            <h2 style="font-size: 22px; font-weight: 700; margin-bottom: 16px; color: #fff;">{{ $product->name }}</h2>
            <div style="font-size: 20px; font-weight: 700; color: #fff; margin-bottom: 24px;">
                ¥{{ number_format($product->price) }} <span style="font-size: 12px; color: #888; font-weight: normal;">（税込）</span>
            </div>

            <div style="font-size: 14px; color: #aaa; line-height: 1.6; margin-bottom: 32px;">
                {!! nl2br(e($product->description)) !!}
            </div>

            {{-- カートに追加するフォーム --}}
            <form action="{{ route('cart.store') }}" method="POST" style="display: flex; gap: 12px; align-items: center;">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="number" name="quantity" value="1" min="1" style="width: 60px; padding: 10px; background-color: #1a1e29; border: 1px solid #2a2e3d; color: #fff; text-align: center;">
                <button type="submit" style="padding: 10px 24px; background-color: #fff; color: #13161E; border: none; font-weight: 700; cursor: pointer; font-size: 14px;">
                    カートに追加
                </button>
            </form>
        </div>
    </div>
</div>
@endsection