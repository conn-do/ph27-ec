@extends('layouts.base')

@section('title', '注文手続き')

@section('content')
<article style="max-width: 700px; margin: 0 auto; padding: 1.5rem;">
    <h2 style="margin-bottom: 1.5rem;">💳 ご注文手続き・お支払い</h2>

    {{-- 🔴 フラッシュメッセージ・共通エラー表示 --}}
    @if (session('error'))
        <div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 0.8rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: bold;">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    <div style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.1rem; border-bottom: 1px solid var(--pico-muted-border-color); padding-bottom: 0.5rem; margin-bottom: 1rem;">
            ご注文内容
        </h3>
        <div style="display: flex; flex-direction: column; gap: 0.8rem;">
            @php $calculatedTotalPrice = 0; @endphp
            @foreach ($items as $item)
                @php
                    $product = $item['product'];
                    $unitPrice = ($product->is_sale && $product->sale_price) ? $product->sale_price : $product->price;
                    $subtotal = $unitPrice * $item['quantity'];
                    $calculatedTotalPrice += $subtotal;
                @endphp
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong>{{ $product->name }}</strong>
                        <div style="font-size: 0.85rem; color: #6b7280; margin-top: 0.2rem; display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap;">
                            @if ($product->is_sale && $product->sale_price)
                                @php
                                    $discountRate = round((($product->price - $product->sale_price) / $product->price) * 100);
                                @endphp
                                <span style="text-decoration: line-through; color: #9ca3af;">
                                    ¥{{ number_format($product->price) }}
                                </span>
                                <strong style="color: #ef4444;">
                                    ¥{{ number_format($product->sale_price) }}
                                </strong>
                                <span style="color: #ef4444; font-size: 0.7rem; font-weight: bold; background: #fee2e2; padding: 0.05rem 0.3rem; border-radius: 4px;">
                                    {{ $discountRate }}% OFF
                                </span>
                                <span>× {{ $item['quantity'] }}個</span>
                            @else
                                <span>¥{{ number_format($product->price) }} × {{ $item['quantity'] }}個</span>
                            @endif
                        </div>
                    </div>
                    <strong>¥{{ number_format($subtotal) }}</strong>
                </div>
            @endforeach
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 1rem; border-radius: 8px; margin-top: 1.5rem;">
            <span style="font-weight: bold; font-size: 1.1rem;">お支払い合計</span>
            <span style="font-size: 1.4rem; font-weight: bold; color: #2563eb;" id="display-total">
                ¥{{ number_format($calculatedTotalPrice) }}
            </span>
        </div>
    </div>

    <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
        @csrf

        {{-- 🎁 ポイント利用エリア --}}
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 1.25rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <h4 style="font-size: 1.05rem; margin-top: 0; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; color: #1e293b;">
                🎁 ポイントの利用
            </h4>

            @php
                $userPoint = auth()->user()->point ?? 0;
            @endphp

            <p style="font-size: 0.9rem; color: #475569; margin-bottom: 0.8rem;">
                現在の保有ポイント: <strong style="color: #2563eb; font-size: 1.1rem;">{{ number_format($userPoint) }}</strong> pt
            </p>

            @if ($userPoint > 0)
                <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                    <label for="use_point" style="font-size: 0.85rem; color: #334155; margin: 0;">
                        使用するポイント数を入力してください（1pt = 1円）
                    </label>
                    
                    <div style="display: flex; align-items: center; gap: 0.5rem; max-width: 200px;">
                        <input 
                            type="number" 
                            id="use_point" 
                            name="use_point" 
                            min="0" 
                            max="{{ $userPoint }}" 
                            value="{{ old('use_point', 0) }}" 
                            placeholder="0"
                            style="margin: 0;"
                        >
                        <span style="font-weight: bold; font-size: 0.95rem;">pt</span>
                    </div>

                    {{-- 🔴 ポイント入力個別エラー表示 --}}
                    @error('use_point')
                        <p style="color: #dc2626; font-size: 0.85rem; margin-top: 0.3rem; margin-bottom: 0; font-weight: bold;">
                            ⚠️ {{ $message }}
                        </p>
                    @enderror

                    <p id="point-limit-notice" style="color: #dc2626; font-size: 0.85rem; margin-top: 0.3rem; margin-bottom: 0; font-weight: bold; display: none;">
                        ⚠️ 利用ポイントが商品合計金額を超えています。
                    </p>
                    
                    <small style="color: #64748b; font-size: 0.8rem; margin-top: 0.2rem;">
                        ※ 保有ポイント（{{ number_format($userPoint) }}pt）または注文合計金額を超えて指定することはできません。
                    </small>
                </div>
            @else
                <p style="font-size: 0.85rem; color: #94a3b8; margin: 0;">
                    ※ 現在ご利用いただけるポイントはありません。
                </p>
            @endif
        </div>

        {{-- 🎁 ギフトラッピングの選択エリア --}}
        <fieldset style="margin-bottom: 1.5rem; background: #fffdfa; padding: 1rem; border: 1px solid #fcd34d; border-radius: 8px;">
            <legend style="font-weight: bold; margin-bottom: 0.5rem; color: #b45309;">🎁 ギフトラッピング希望</legend>
            <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; cursor: pointer;">
                <input type="radio" name="gift_option" value="none" checked>
                希望しない
            </label>
            <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; cursor: pointer;">
                <input type="radio" name="gift_option" value="ribbon">
                簡易リボンラッピング（無料）
            </label>
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="radio" name="gift_option" value="box">
                ギフトBOX＆メッセージカード付き（+¥300）
            </label>
        </fieldset>

        {{-- 💳 お支払い方法の選択エリア --}}
        <fieldset style="margin-bottom: 1.5rem;">
            <legend style="font-weight: bold; margin-bottom: 0.5rem;">お支払い方法を選択</legend>
            
            <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; cursor: pointer;">
                <input type="radio" name="payment_method" value="credit" checked onchange="toggleCardForm(true)">
                クレジットカード決済
            </label>
            
            <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; cursor: pointer;">
                <input type="radio" name="payment_method" value="bank" onchange="toggleCardForm(false)">
                銀行振込
            </label>
            
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="radio" name="payment_method" value="cod" onchange="toggleCardForm(false)">
                代金引換
            </label>
        </fieldset>

        {{-- クレジットカード情報入力エリア（疑似） --}}
        <div id="credit-card-info" style="background: #f1f5f9; padding: 1.2rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <h4 style="font-size: 1rem; margin-bottom: 0.8rem;">💳 クレジットカード情報の入力</h4>
            <div style="display: flex; flex-direction: column; gap: 0.8rem;">
                <div>
                    <label style="font-size: 0.85rem; margin-bottom: 0.2rem; display: block;">カード番号</label>
                    <input type="text" placeholder="1234 5678 9012 3456" maxlength="19">
                </div>
                <div style="display: flex; gap: 1rem;">
                    <div style="flex: 1;">
                        <label style="font-size: 0.85rem; margin-bottom: 0.2rem; display: block;">有効期限</label>
                        <input type="text" placeholder="MM/YY" maxlength="5">
                    </div>
                    <div style="flex: 1;">
                        <label style="font-size: 0.85rem; margin-bottom: 0.2rem; display: block;">セキュリティコード</label>
                        <input type="text" placeholder="123" maxlength="4">
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" style="width: 100%; font-weight: bold; font-size: 1.05rem; padding: 0.8rem;">
            注文を確定する
        </button>
    </form>
    
    <div style="text-align: center; margin-top: 1rem;">
        <a href="/cart" class="secondary outline" style="font-size: 0.9rem;">← カートに戻る</a>
    </div>
</article>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const baseTotal = {{ $calculatedTotalPrice }};
    const maxUserPoint = {{ auth()->user()->point ?? 0 }};
    const displayTotal = document.getElementById('display-total');
    const giftRadios = document.querySelectorAll('input[name="gift_option"]');
    const usePointInput = document.getElementById('use_point');
    const pointLimitNotice = document.getElementById('point-limit-notice');

    function calculateTotal() {
        // 1. ギフト手数料の計算
        let giftFee = 0;
        const selectedGift = document.querySelector('input[name="gift_option"]:checked');
        if (selectedGift && selectedGift.value === 'box') {
            giftFee = 300;
        }

        const currentTotalPrice = baseTotal + giftFee;

        // 2. 利用ポイントの計算と上限チェック
        let usedPoint = 0;
        if (usePointInput) {
            usedPoint = parseInt(usePointInput.value) || 0;
            
            // ポイント上限（所持ポイントまたは合計金額の小さい方）
            const maxAllowed = Math.min(maxUserPoint, currentTotalPrice);

            if (usedPoint > maxAllowed) {
                usedPoint = maxAllowed;
                usePointInput.value = maxAllowed;
                if (pointLimitNotice) pointLimitNotice.style.display = 'block';
            } else if (usedPoint < 0) {
                usedPoint = 0;
                usePointInput.value = 0;
                if (pointLimitNotice) pointLimitNotice.style.display = 'none';
            } else {
                if (pointLimitNotice) pointLimitNotice.style.display = 'none';
            }
        }

        // 3. 最終金額の計算
        const grandTotal = Math.max(0, currentTotalPrice - usedPoint);
        displayTotal.textContent = '¥' + grandTotal.toLocaleString();
    }

    // ギフトの変更時に再計算
    giftRadios.forEach(radio => {
        radio.addEventListener('change', calculateTotal);
    });

    // ポイント入力時に再計算
    if (usePointInput) {
        usePointInput.addEventListener('input', calculateTotal);
    }
});

function toggleCardForm(show) {
    const cardInfo = document.getElementById('credit-card-info');
    if (cardInfo) {
        cardInfo.style.display = show ? 'block' : 'none';
    }
}
</script>
@endsection