@extends('layouts.base')

@section('title', '注文詳細')

@section('content')

<div style="max-width: 800px; margin: 40px auto; color: #e5e7eb; padding: 0 20px;">

    <h2 style="font-size: 20px; font-weight: 600; color: #e5e7eb; margin-top: 0; margin-bottom: 30px; border-left: 4px solid #38bdf8; padding-left: 12px;">
        注文詳細
    </h2>

    <div style="background: #13161E; border: 1px solid #2a3245; padding: 24px; border-radius: 4px; margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #2a3245; padding-bottom: 16px;">
            <div>
                <span style="font-size: 13px; color: #9ca3af; display: block; margin-bottom: 4px;">注文ID</span>
                <span style="font-size: 18px; font-weight: 600; color: #fff;">#{{ $order->id }}</span>
            </div>
            <div style="text-align: right;">
                <span style="font-size: 13px; color: #9ca3af; display: block; margin-bottom: 4px;">注文日時</span>
                <span style="font-size: 14px; color: #e5e7eb;">{{ $order->created_at }}</span>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <div style="font-size: 13px; color: #9ca3af; margin-bottom: 10px;">購入商品</div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                @foreach($order->items as $item)
                    <div style="display: flex; justify-content: space-between; align-items: center; background: #0e1117; padding: 12px 16px; border-radius: 4px; border: 1px solid #2a3245;">
                        <span style="font-size: 14px; color: #fff;">{{ $item->product->name ?? '商品名' }}</span>
                        <span style="font-size: 13px; color: #9ca3af;">数量: {{ $item->quantity }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #2a3245; padding-top: 16px;">
            <span style="font-size: 14px; color: #9ca3af;">合計金額</span>
            <span style="font-size: 20px; font-weight: 600; color: #38bdf8;">¥{{ number_format($order->total_price) }}</span>
        </div>
    </div>

    <div>
        <a href="/orders" style="display: inline-block; padding: 10px 24px; background: transparent; color: #38bdf8; border: 1px solid #38bdf8; font-size: 14px; text-decoration: none; border-radius: 4px;">
            &larr; 注文履歴一覧に戻る
        </a>
    </div>

</div>

@endsection