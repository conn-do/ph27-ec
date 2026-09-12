@extends('layouts.base')

@section('title', '配送状況の確認')

@section('content')
<article style="max-width: 700px; margin: 0 auto; padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2>🚚 配送状況の確認</h2>
        <a href="/mypage" class="secondary outline" style="font-size: 0.85rem;">← マイページへ戻る</a>
    </div>

    <div style="background: #f8fafc; padding: 1.2rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #e2e8f0;">
        <p style="margin: 0 0 0.5rem 0;"><strong>注文番号：</strong> #{{ $order->id }}</p>
        <p style="margin: 0 0 0.5rem 0;"><strong>注文日時：</strong> {{ $order->created_at->format('Y/m/d H:i') }}</p>
        <p style="margin: 0;"><strong>配送伝票番号：</strong> {{ $order->status === 'shipped' || $order->status === 'delivered' ? '1234-5678-9012（ヤマト運輸）' : '未発行' }}</p>
    </div>

    @php
        $status = $order->status ?? 'ordered';
    @endphp

    <div style="display: flex; justify-content: space-between; align-items: center; position: relative; margin: 2rem 0 3rem 0; padding: 0 1rem;">
        {{-- ステップ1: 注文完了 --}}
        <div style="text-align: center; z-index: 1;">
            <div style="width: 40px; height: 40px; border-radius: 50%; background: #2563eb; color: #fff; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem auto; font-weight: bold;">✓</div>
            <span style="font-size: 0.85rem; font-weight: bold;">注文完了</span>
        </div>

        {{-- ステップ2: 発送準備中 --}}
        <div style="text-align: center; z-index: 1;">
            <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ in_array($status, ['shipping_preparing', 'shipped', 'delivered']) ? '#2563eb' : '#cbd5e1' }}; color: #fff; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem auto; font-weight: bold;">
                {{ in_array($status, ['shipping_preparing', 'shipped', 'delivered']) ? '✓' : '2' }}
            </div>
            <span style="font-size: 0.85rem; font-weight: bold;">発送準備中</span>
        </div>

        {{-- ステップ3: 発送済み --}}
        <div style="text-align: center; z-index: 1;">
            <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ in_array($status, ['shipped', 'delivered']) ? '#2563eb' : '#cbd5e1' }}; color: #fff; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem auto; font-weight: bold;">
                {{ in_array($status, ['shipped', 'delivered']) ? '✓' : '3' }}
            </div>
            <span style="font-size: 0.85rem; font-weight: bold;">発送済み</span>
        </div>

        {{-- ステップ4: 配達完了 --}}
        <div style="text-align: center; z-index: 1;">
            <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ $status === 'delivered' ? '#2563eb' : '#cbd5e1' }}; color: #fff; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem auto; font-weight: bold;">
                {{ $status === 'delivered' ? '✓' : '4' }}
            </div>
            <span style="font-size: 0.85rem; font-weight: bold;">配達完了</span>
        </div>
    </div>

    <h3>ご注文商品一覧</h3>
    <div style="display: flex; flex-direction: column; gap: 0.8rem; margin-top: 1rem;">
        @foreach ($order->details as $detail)
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 0.8rem;">
                <div>
                    <strong>{{ optional($detail->product)->name ?? '削除された商品' }}</strong>
                    <small style="color: #6b7280; display: block;">数量: {{ $detail->quantity }}</small>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <strong>¥{{ number_format(($detail->price ?? optional($detail->product)->price ?? 0) * $detail->quantity) }}</strong>
                    
                    {{-- 配達完了時に商品詳細ページへのリンクを表示 --}}
                    @if ($order->status === 'delivered' && $detail->product)
                        <a href="/products/{{ $detail->product->id }}" class="secondary outline" style="display: inline-block; padding: 0.3rem 0.7rem; font-size: 0.8rem; text-decoration: none; white-space: nowrap; margin: 0;">
                            もう一度購入
                        </a>
                    @endif
                </div>
            </div>
        @endforeach

        {{-- 🎁 ギフトラッピング表示 --}}
        @if (($order->gift_option ?? '') === 'box')
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 0.8rem; color: #d97706;">
                <div>
                    <strong>🎁 ギフトBOX＆メッセージカード</strong>
                </div>
                <strong>+¥300</strong>
            </div>
        @elseif (in_array($order->gift_option ?? '', ['ribbon', 'free']))
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 0.8rem; color: #16a34a;">
                <div>
                    <strong>🎀 簡易リボンラッピング</strong>
                </div>
                <strong>¥0</strong>
            </div>
        @endif

        {{-- 🎁 ポイント利用額表示 --}}
        @if (($order->used_point ?? 0) > 0)
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 0.8rem; color: #dc2626;">
                <div>
                    <strong>🎁 ポイント値引き ({{ number_format($order->used_point) }}pt)</strong>
                </div>
                <strong>-¥{{ number_format($order->used_point) }}</strong>
            </div>
        @endif

        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 0.5rem; font-size: 1.1rem;">
            <strong>お支払い合計金額</strong>
            <strong style="color: #2563eb;">¥{{ number_format($order->total_price) }}</strong>
        </div>
    </div>
</article>
@endsection