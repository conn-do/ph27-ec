@extends('layouts.base')

@section('title', '注文履歴')

@section('content')

<div style="max-width: 800px; margin: 40px auto; color: #e5e7eb; padding: 0 20px;">

    <h2 style="font-size: 20px; font-weight: 600; color: #e5e7eb; margin-top: 0; margin-bottom: 30px; border-left: 4px solid #38bdf8; padding-left: 12px;">
        注文履歴
    </h2>

    @if(isset($orders) && count($orders) > 0)
        <div style="display: flex; flex-direction: column; gap: 16px;">
            @foreach ($orders as $order)
                <div style="display: flex; justify-content: space-between; align-items: center; background: #13161E; border: 1px solid #2a3245; padding: 20px; border-radius: 4px;">
                    <div>
                        <div style="font-size: 13px; color: #9ca3af; margin-bottom: 4px;">
                            注文ID: {{ $order->id }}
                        </div>
                        <div style="font-size: 15px; font-weight: 600; color: #38bdf8;">
                            ¥{{ number_format($order->total_price) }}
                        </div>
                    </div>
                    <div>
                        <a href="/orders/{{ $order->id }}" style="padding: 8px 16px; background: transparent; color: #38bdf8; border: 1px solid #38bdf8; font-size: 13px; text-decoration: none; border-radius: 4px; transition: all 0.2s;">
                            詳細を見る
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 60px 0; color: #9ca3af; font-size: 14px;">
            <p style="margin-bottom: 25px;">注文履歴はまだありません。</p>
            <a href="/" style="display: inline-block; padding: 10px 24px; background: transparent; color: #38bdf8; border: 1px solid #38bdf8; font-size: 14px; text-decoration: none; border-radius: 4px;">
                買い物に行く
            </a>
        </div>
    @endif

</div>

@endsection