@extends('layouts.base')

@section('title', 'ショッピングカート')

@section('content')
<h2 style="font-size: 24px; font-weight: 700; margin-bottom: 32px; color: #fff;">ショッピングカート</h2>

<div style="display: flex; flex-direction: column; gap: 24px; margin-bottom: 40px;">
    @forelse ($cartItems ?? [] as $item)
        <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 24px; border-bottom: 1px solid #2a2e3d; background-color: #13161E;">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="width: 80px; height: 80px; background-color: #13161E; flex-shrink: 0;">
                    <img src="{{ $item->product->imageUrl() }}" alt="{{ $item->product->name }}" style="width: 100%; height: 100%; object-fit: cover; filter: grayscale(100%); display: block;">
                </div>
                <div>
                    <h3 style="font-size: 16px; font-weight: 700; color: #fff; margin-bottom: 4px;">{{ $item->product->name }}</h3>
                    <div style="font-size: 14px; color: #888;">¥{{ number_format($item->product->price) }} × {{ $item->quantity }}点</div>
                </div>
            </div>
            
            <form action="{{ route('cart.destroy', $item->id) }}" method="POST" style="margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" style="background: none; border: none; color: #888; cursor: pointer; font-size: 13px;">削除</button>
            </form>
        </div>
    @empty
        <div style="color: #666; font-size: 14px; padding: 20px 0;">
            カートに商品はありません。
        </div>
    @endforelse
</div>

@if(isset($cartItems) && count($cartItems) > 0)
    <div style="border-top: 1px solid #2a2e3d; padding-top: 24px; display: flex; justify-content: space-between; align-items: center; background-color: #13161E;">
        <div style="font-size: 18px; font-weight: 700; color: #fff;">
            合計金額: ¥{{ number_format($totalPrice ?? 0) }}
        </div>
        <form action="{{ route('orders.store') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="ec-btn-primary">
                注文を確定する
            </button>
        </form>
    </div>
@endif
@endsection