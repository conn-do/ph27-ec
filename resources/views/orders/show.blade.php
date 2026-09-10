@extends('layouts.base')
@section('title', '注文詳細')
@section('content')
    <div class="page-heading">
        <p class="eyebrow">
            ORDER DETAILS
        </p>
        <h1>
            注文 #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
        </h1>
        <p>
            {{ $order->created_at->format('Y年m月d日 H:i') }} / 受付済み
        </p>
    </div>
    <div class="purchase-layout">
        <section>
            <h2>
                ご注文の商品
            </h2>
            <div class="ordered-items">
                @foreach($order->details as $detail)
                    <div>
                        <span>
                            {{ $detail->product_name ?? $detail->product?->name ?? '販売終了商品' }}
                            <small>
                                {{ $detail->quantity }}点
                                @if($detail->unit_price !== null)
                                    / 単価 ¥{{ number_format($detail->unit_price) }}
                                @else
                                    / 注文時単価の記録なし
                                @endif
                            </small>
                        </span>
                        <strong>
                            @if($detail->unit_price !== null)
                                ¥{{ number_format($detail->unit_price * $detail->quantity) }}
                            @endif
                        </strong>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('orders.index') }}">
                ← 注文履歴へ
            </a>
        </section>
        <aside class="order-summary">
            <h2>
                ご注文内容
            </h2>
            <dl>
                <div>
                    <dt>
                        送料
                    </dt>
                    <dd>
                        ¥{{ number_format($order->shipping_fee) }}
                    </dd>
                </div>
                <div class="total">
                    <dt>
                        合計（税込）
                    </dt>
                    <dd>
                        ¥{{ number_format($order->total_price) }}
                    </dd>
                </div>
            </dl>
            @if($order->recipient_name)
                <h3>
                    お届け先
                </h3>
                <p class="address">
                    {{ $order->recipient_name }} 様
                    <br>
                    〒{{ $order->postal_code }}
                    <br>
                    {{ $order->address }}
                    <br>
                    {{ $order->phone }}
                </p>
            @else
                <p>
                    この注文にはお届け先の記録がありません。
                </p>
            @endif
            <p class="demo-note">
                デモ注文 · 実際の決済・配送は行われません。
            </p>
            <a class="button button-outline full-width" href="{{ route('home') }}">
                お買い物を続ける
            </a>
        </aside>
    </div>
@endsection
