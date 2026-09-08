@extends('layouts.base')

@section('title', 'マイページ')

@section('content')

<div style="max-width: 800px; margin: 40px auto; color: #e5e7eb; padding: 0 20px;">

    <h2 style="font-size: 20px; font-weight: 600; color: #e5e7eb; margin-top: 0; margin-bottom: 30px; border-left: 4px solid #38bdf8; padding-left: 12px;">
        マイページ
    </h2>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
        <a href="/orders" style="background: #13161E; border: 1px solid #2a3245; padding: 24px; border-radius: 4px; text-decoration: none; color: #fff; transition: border-color 0.2s; display: block;">
            <div style="font-size: 16px; font-weight: 600; color: #38bdf8; margin-bottom: 8px;">注文履歴</div>
            <div style="font-size: 13px; color: #9ca3af;">過去の注文内容や詳細を確認できます。</div>
        </a>

        <a href="/" style="background: #13161E; border: 1px solid #2a3245; padding: 24px; border-radius: 4px; text-decoration: none; color: #fff; transition: border-color 0.2s; display: block;">
            <div style="font-size: 16px; font-weight: 600; color: #38bdf8; margin-bottom: 8px;">買い物を続ける</div>
            <div style="font-size: 13px; color: #9ca3af;">ストアに戻って商品を探す。</div>
        </a>
    </div>

</div>

@endsection