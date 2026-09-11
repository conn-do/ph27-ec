@extends('layouts.base')

@section('title', '注文完了')

@section('content')

<div style="max-width: 800px; margin: 40px auto; color: #e5e7eb; padding: 0 20px;">

    <h2 style="font-size: 20px; font-weight: 600; color: #e5e7eb; margin-top: 0; margin-bottom: 30px; border-left: 4px solid #38bdf8; padding-left: 12px;">
        ご注文完了
    </h2>

    <div style="background: #13161E; border: 1px solid #2a3245; padding: 30px; border-radius: 4px; text-align: center; margin-bottom: 30px;">
        <div style="font-size: 18px; font-weight: 600; color: #38bdf8; margin-bottom: 12px;">
            注文が完了しました！
        </div>
        <div style="font-size: 14px; color: #9ca3af; margin-bottom: 24px;">
            注文ID: {{ $order->id ?? '' }}
        </div>
        <div style="font-size: 13px; color: #e5e7eb;">
            ご注文ありがとうございます。マイページから注文履歴をご確認いただけます。
        </div>
    </div>

    <div style="display: flex; gap: 15px; justify-content: center;">
        <a href="/orders" style="padding: 10px 24px; background: transparent; color: #38bdf8; border: 1px solid #38bdf8; font-size: 14px; text-decoration: none; border-radius: 4px;">
            注文履歴を見る
        </a>
        <a href="/" style="padding: 10px 24px; background: #38bdf8; color: #0e1117; font-size: 14px; font-weight: 600; text-decoration: none; border-radius: 4px;">
            買い物を続ける
        </a>
    </div>

</div>

@endsection