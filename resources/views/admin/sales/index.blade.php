@extends('layouts.base')

@section('title', '商品・セール管理（管理者画面）')

@section('content')
<article style="max-width: 1100px; margin: 0 auto; padding: 1.5rem;">
    {{-- ヘッダー & ナビゲーション --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <h2 style="margin: 0; font-size: 1.5rem;">🏷️ 商品・セール価格管理</h2>
        
        {{-- 管理画面ナビゲーションボタン --}}
        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
            <a href="/admin/orders" class="secondary outline" style="font-size: 0.85rem; padding: 0.4rem 0.8rem; width: auto; margin: 0; text-decoration: none;">
                📦 注文・配送管理へ
            </a>
            <a href="/admin/sales" class="primary" style="font-size: 0.85rem; padding: 0.4rem 0.8rem; width: auto; margin: 0; text-decoration: none;">
                🏷️ セール価格管理
            </a>
            <a href="/mypage" class="secondary outline" style="font-size: 0.85rem; padding: 0.4rem 0.8rem; width: auto; margin: 0; text-decoration: none;">
                ← マイページへ戻る
            </a>
        </div>
    </div>

    @if (session('message'))
        <ins style="color: #16a34a; display: block; margin-bottom: 1.5rem; background: #f0fdf4; padding: 0.75rem 1rem; border-radius: 6px; border: 1px solid #bbf7d0; text-decoration: none; font-size: 0.9rem;">
            ✓ {{ session('message') }}
        </ins>
    @endif

    <div style="background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; margin: 0;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                    <th style="padding: 0.8rem 1rem; width: 70px; text-align: center;">画像</th>
                    <th style="padding: 0.8rem 1rem;">商品名</th>
                    <th style="padding: 0.8rem 1rem; width: 120px; text-align: right;">通常価格</th>
                    <th style="padding: 0.8rem 1rem; width: 140px;">割引率</th>
                    <th style="padding: 0.8rem 1rem; width: 130px; text-align: right;">セール価格(参考)</th>
                    <th style="padding: 0.8rem 1rem; width: 110px; text-align: center;">セール状態</th>
                    <th style="padding: 0.8rem 1rem; width: 90px; text-align: center;">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    @php
                        // 現在の割引率を逆算して初期表示（通常価格とセール価格から算出）
                        $discountPercent = 0;
                        if ($product->price > 0 && $product->sale_price) {
                            $discountPercent = round((( $product->price - $product->sale_price ) / $product->price) * 100);
                        }
                    @endphp
                    <tr class="product-row" style="border-bottom: 1px solid #f1f5f9; vertical-align: middle;">
                        {{-- 画像 --}}
                        <td style="padding: 0.8rem 1rem; text-align: center; vertical-align: middle;">
                            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0; display: block; margin: 0 auto;">
                        </td>

                        {{-- 商品名 --}}
                        <td style="padding: 0.8rem 1rem; vertical-align: middle; font-weight: bold; color: #1e293b;">
                            {{ $product->name }}
                        </td>

                        {{-- 通常価格 --}}
                        <td style="padding: 0.8rem 1rem; vertical-align: middle; text-align: right; color: #64748b;">
                            ¥<span class="normal-price" data-price="{{ $product->price }}">{{ number_format($product->price) }}</span>
                        </td>

                        {{-- 割引率入力 (%OFF) --}}
                        <td style="padding: 0.8rem 1rem; vertical-align: middle;">
                            <div style="display: flex; align-items: center; gap: 0.3rem;">
                                <input 
                                    type="number" 
                                    name="discount_percent" 
                                    form="sale-form-{{ $product->id }}" 
                                    value="{{ $discountPercent > 0 ? $discountPercent : '' }}" 
                                    placeholder="20" 
                                    min="0"
                                    max="99"
                                    class="discount-input"
                                    style="margin: 0; padding: 0.35rem 0.5rem; font-size: 0.85rem; width: 100%; border-radius: 4px; border: 1px solid #cbd5e1;"
                                >
                                <span style="font-size: 0.85rem; color: #64748b; font-weight: bold;">% OFF</span>
                            </div>
                        </td>

                        {{-- セール価格表示（リアルタイム更新対象） --}}
                        <td style="padding: 0.8rem 1rem; vertical-align: middle; text-align: right; font-weight: bold;">
                            <span class="sale-price-display" style="color: #ef4444;">
                                @if ($product->is_sale && $product->sale_price)
                                    ¥{{ number_format($product->sale_price) }}
                                @else
                                    <span style="color: #94a3b8; font-weight: normal;">-</span>
                                @endif
                            </span>
                        </td>

                        {{-- セール状態チェック --}}
                        <td style="padding: 0.8rem 1rem; vertical-align: middle; text-align: center;">
                            <label style="margin: 0; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.3rem; white-space: nowrap;">
                                <input 
                                    type="checkbox" 
                                    name="is_sale" 
                                    form="sale-form-{{ $product->id }}" 
                                    value="1" 
                                    class="sale-checkbox"
                                    {{ $product->is_sale ? 'checked' : '' }} 
                                    style="margin: 0;"
                                >
                                セール中
                            </label>
                        </td>

                        {{-- 保存ボタン --}}
                        <td style="padding: 0.8rem 1rem; vertical-align: middle; text-align: center;">
                            <form id="sale-form-{{ $product->id }}" action="{{ route('admin.products.updateSale', $product) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="margin: 0; padding: 0.35rem 0.75rem; font-size: 0.85rem; width: auto; white-space: nowrap; display: inline-block;">保存</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</article>

{{-- リアルタイム計算スクリプト --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rows = document.querySelectorAll('.product-row');

    rows.forEach(row => {
        const priceElement = row.querySelector('.normal-price');
        const discountInput = row.querySelector('.discount-input');
        const salePriceDisplay = row.querySelector('.sale-price-display');

        if (!priceElement || !discountInput || !salePriceDisplay) return;

        const normalPrice = parseFloat(priceElement.dataset.price);

        function updateSalePrice() {
            const discountPercent = parseFloat(discountInput.value);

            if (!isNaN(discountPercent) && discountPercent > 0 && discountPercent < 100) {
                const calculatedPrice = Math.round(normalPrice * (1 - discountPercent / 100));
                salePriceDisplay.style.color = '#ef4444';
                salePriceDisplay.style.fontWeight = 'bold';
                salePriceDisplay.textContent = '¥' + calculatedPrice.toLocaleString();
            } else {
                salePriceDisplay.innerHTML = '<span style="color: #94a3b8; font-weight: normal;">-</span>';
            }
        }

        discountInput.addEventListener('input', updateSalePrice);
    });
});
</script>
@endsection