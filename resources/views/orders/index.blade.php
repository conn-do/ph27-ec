@extends('layouts.base')

@section('title', '注文履歴')

@section('content')
<h2 style="font-size: 24px; font-weight: 700; margin-bottom: 32px; color: #fff;">注文履歴</h2>

<div style="display: flex; flex-direction: column; gap: 32px;">
    @forelse ($orders ?? [] as $order)
        <div style="background-color: #13161E; border: 1px solid #2a2e3d; padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #2a2e3d; padding-bottom: 12px;">
                <div style="font-size: 13px; color: #888;">
                    注文日時：{{ $order->created_at->format('Y年m月d日 H:i') }}
                </div>
                <div style="font-size: 13px; font-weight: 700; color: #fff;">
                    合計：¥{{ number_format($order->total_price) }}
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 16px;">
                @foreach ($order->orderItems ?? [] as $detail)
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="width: 60px; height: 60px; background-color: #13161E; flex-shrink: 0;">
                            <img src="{{ $detail->product->imageUrl() }}" alt="{{ $detail->product->name }}" style="width: 100%; height: 100%; object-fit: cover; filter: grayscale(100%); display: block;">
                        </div>
                        <div style="flex-grow: 1;">
                            <div style="font-size: 14px; font-weight: 700; color: #fff; margin-bottom: 2px;">{{ $detail->product->name }}</div>
                            <div style="font-size: 12px; color: #888;">¥{{ number_format($detail->price) }} × {{ $detail->quantity }}点</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div style="color: #666; font-size: 14px; padding: 20px 0;">
            注文履歴はありません。
        </div>
    @endforelse
</div>
@endsection