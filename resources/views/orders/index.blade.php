@extends('layouts.base')

@section('title', '過去の注文履歴')

@section('content')
<article style="max-width: 800px; margin: 0 auto; padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <h2>📜 過去の注文履歴</h2>
        <a href="/mypage" class="secondary outline" style="font-size: 0.85rem;">← マイページへ戻る</a>
    </div>

    {{-- 期間フィルター --}}
    <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
        <span style="font-weight: bold; font-size: 0.9rem; color: #475569;">期間指定で表示：</span>
        <form action="{{ route('orders.index') }}" method="GET" style="margin: 0; display: flex; align-items: center;">
            <select name="period" onchange="this.form.submit()" style="margin: 0; padding: 0.4rem 0.8rem; font-size: 0.9rem; width: auto;">
                <option value="" {{ empty($period) ? 'selected' : '' }}>すべての期間</option>
                <option value="3months" {{ $period === '3months' ? 'selected' : '' }}>過去3ヶ月以内の注文</option>
                <option value="6months" {{ $period === '6months' ? 'selected' : '' }}>過去6ヶ月以内の注文</option>
            </select>
        </form>
    </div>

    @if (isset($orders) && $orders->count() > 0)
        <div style="display: flex; flex-direction: column; gap: 1.2rem;">
            @foreach ($orders as $order)
                <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.8rem; margin-bottom: 0.8rem; flex-wrap: wrap; gap: 0.5rem;">
                        <div>
                            <strong>注文番号: #{{ $order->id }}</strong>
                            <small style="color: #6b7280; display: block;">注文日時: {{ $order->created_at->format('Y/m/d H:i') }}</small>
                        </div>
                        <a href="{{ route('orders.tracking', $order->id) }}" role="button" class="outline" style="font-size: 0.85rem; padding: 0.35rem 0.8rem; margin: 0; text-decoration: none;">
                            🚚 配送状況を確認
                        </a>
                    </div>

                    {{-- 注文商品一覧 --}}
                    <div style="font-size: 0.9rem; color: #475569; margin-bottom: 0.8rem;">
                        @foreach ($order->details as $detail)
                            @php
                                $product = $detail->product;
                            @endphp
                            <div style="margin-bottom: 0.2rem; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                <span>・{{ optional($product)->name ?? '削除された商品' }} × {{ $detail->quantity }}個</span>
                                
                                {{-- セール割引表示 --}}
                                @if ($product && $product->is_sale && $product->sale_price)
                                    @php
                                        $discountRate = round((($product->price - $product->sale_price) / $product->price) * 100);
                                    @endphp
                                    <span style="color: #ef4444; font-size: 0.75rem; font-weight: bold; background: #fee2e2; padding: 0.1rem 0.3rem; border-radius: 4px;">
                                        {{ $discountRate }}% OFF セール品
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div style="text-align: right; font-weight: bold; color: #2563eb; font-size: 1.05rem;">
                        合計: ¥{{ number_format($order->total_price) }}
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p style="color: #6b7280; text-align: center; padding: 2rem 0;">指定された期間の注文履歴はありません。</p>
    @endif
</article>
@endsection