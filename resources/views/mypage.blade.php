@extends('layouts.base')

@section('title', 'マイページ')

@section('content')
<div style="background-color: #13161E; color: #fff; max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 40px;">

    <h2 style="font-size: 24px; font-weight: 700; color: #fff; margin: 0;">マイページ</h2>

    {{-- 登録情報セクション --}}
    <div style="background-color: #1a1e29; padding: 24px; border: 1px solid #2a2e3d;">
        <h3 style="font-size: 16px; font-weight: 700; color: #fff; margin-bottom: 16px;">登録情報</h3>
        <div style="display: flex; flex-direction: column; gap: 8px; font-size: 14px; color: #aaa;">
            <div>氏名：<span style="color: #fff;">{{ Auth::user()->name ?? '-' }}</span></div>
            <div>メールアドレス：<span style="color: #fff;">{{ Auth::user()->email ?? '-' }}</span></div>
        </div>
    </div>

    {{-- 注文履歴セクション --}}
    <div>
        <h3 style="font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 20px; border-bottom: 1px solid #2a2e3d; padding-bottom: 8px;">注文履歴</h3>

        <div style="display: flex; flex-direction: column; gap: 16px;">
            @forelse ($orders ?? [] as $order)
                <div style="background-color: #1a1e29; border: 1px solid #2a2e3d; padding: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; font-size: 13px; color: #888; border-bottom: 1px solid #2a2e3d; padding-bottom: 8px;">
                        <span>注文日時：{{ $order->created_at->format('Y年m月d日 H:i') }}</span>
                        <span style="color: #fff; font-weight: 700;">合計：¥{{ number_format($order->total_price) }}</span>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        @foreach ($order->orderItems ?? [] as $detail)
                            <div style="font-size: 14px; color: #fff; display: flex; justify-content: space-between;">
                                <span>{{ $detail->product->name ?? '商品名なし' }} × {{ $detail->quantity }}点</span>
                                <span style="color: #888;">¥{{ number_format($detail->price) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div style="color: #666; font-size: 14px; padding: 10px 0;">
                    注文履歴はありません。
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection