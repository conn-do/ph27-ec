@extends('layouts.base')

@section('title', 'ショッピングカート')

@section('content')
    {{-- 外枠に max-width: 900px と margin: 0 auto を設定して中央寄せにします --}}
    <article style="max-width: 900px; margin: 0 auto; padding: 1.5rem; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        {{-- タイトル & 右上の商品一覧に戻るボタン --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <h2 style="margin: 0; font-size: 1.5rem;">🛒 ショッピングカート</h2>

            {{-- 右上：商品一覧に戻るボタン --}}
            <a href="/" class="secondary outline" style="width: auto; padding: 0.4rem 0.9rem; font-size: 0.85rem; text-decoration: none; margin: 0;">
                ← 商品一覧に戻る
            </a>
        </div>

        <!-- メッセージ表示 -->
        @if (session('message'))
            <ins style="color: #16a34a; display: block; margin-bottom: 1.5rem; font-weight: bold; text-decoration: none;">{{ session('message') }}</ins>
        @endif
        @if (session('error'))
            <del style="color: #dc2626; display: block; margin-bottom: 1.5rem; font-weight: bold; text-decoration: none;">{{ session('error') }}</del>
        @endif

        @if (isset($items) && count($items) > 0)
            @php $calculatedTotalPrice = 0; @endphp
            <table style="margin-bottom: 2rem; width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 0.75rem 0.5rem; text-align: left;">商品</th>
                        <th style="padding: 0.75rem 0.5rem; text-align: right;">単価</th>
                        <th style="padding: 0.75rem 0.5rem; text-align: center; width: 100px;">数量</th>
                        <th style="padding: 0.75rem 0.5rem; text-align: right;">小計</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        @php
                            $product = $item['product'];
                            $unitPrice = ($product->is_sale && $product->sale_price) ? $product->sale_price : $product->price;
                            $subtotal = $unitPrice * $item['quantity'];
                            $calculatedTotalPrice += $subtotal;
                        @endphp
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.75rem 0.5rem;">
                                <div style="display: flex; align-items: center; gap: 0.8rem;">
                                    @if ($product->image)
                                        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
                                    @endif
                                    <strong>{{ $product->name }}</strong>
                                </div>
                            </td>
                            <td style="padding: 0.75rem 0.5rem; text-align: right;">
                                {{-- 単価表示・セール対応 --}}
                                @if ($product->is_sale && $product->sale_price)
                                    @php
                                        $discountRate = round((($product->price - $product->sale_price) / $product->price) * 100);
                                    @endphp
                                    <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.2rem;">
                                        <span style="text-decoration: line-through; color: #9ca3af; font-size: 0.8rem;">
                                            ¥{{ number_format($product->price) }}
                                        </span>
                                        <div style="display: flex; align-items: center; gap: 0.3rem;">
                                            <strong style="color: #ef4444;">
                                                ¥{{ number_format($product->sale_price) }}
                                            </strong>
                                            <span style="color: #ef4444; font-size: 0.7rem; font-weight: bold; background: #fee2e2; padding: 0.1rem 0.3rem; border-radius: 4px;">
                                                {{ $discountRate }}% OFF
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    ¥{{ number_format($product->price) }}
                                @endif
                            </td>
                            <td style="padding: 0.75rem 0.5rem; text-align: center;">{{ $item['quantity'] }}個</td>
                            <td style="padding: 0.75rem 0.5rem; text-align: right; font-weight: bold;">¥{{ number_format($subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- クーポン入力フォームエリア -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 1.2rem; border-radius: 8px; margin-bottom: 2rem;">
                <form action="{{ route('cart.coupon') }}" method="POST" style="margin: 0; display: flex; gap: 0.8rem; align-items: center; flex-wrap: wrap;">
                    @csrf
                    <input type="text" name="coupon_code" placeholder="クーポンコードを入力" style="margin: 0; flex-grow: 1; max-width: 300px;" required>
                    <button type="submit" class="secondary" style="width: auto; margin: 0; white-space: nowrap; padding: 0.5rem 1.2rem;">
                        クーポンを適用
                    </button>
                </form>

                {{-- 適用中のクーポン表示 --}}
                @if (session('coupon'))
                    <div style="margin-top: 0.8rem; color: #16a34a; font-size: 0.9rem; font-weight: bold; display: flex; align-items: center; gap: 0.5rem;">
                        <span>🎟 クーポン [ {{ session('coupon')['code'] }} ] を適用しました</span>
                        @if (session('coupon')['discount_amount'] > 0)
                            <span>(-¥{{ number_format(session('coupon')['discount_amount']) }})</span>
                        @elseif (session('coupon')['discount_rate'] > 0)
                            <span>({{ session('coupon')['discount_rate'] }}% OFF)</span>
                        @endif
                    </div>
                @endif
            </div>

            <!-- 合計金額 & アクションボタンエリア -->
            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 2px solid #e2e8f0; padding-top: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                {{-- 左下：カートを空にするボタン --}}
                <form action="/cart/clear" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="secondary outline" style="width: auto; padding: 0.4rem 0.8rem; font-size: 0.85rem; margin: 0;">
                        カートを空にする
                    </button>
                </form>

                {{-- 右下：合計金額 & 購入手続きへ進む --}}
                <div style="text-align: right; display: flex; align-items: center; gap: 1.5rem;">
                    <div style="font-size: 1.1rem;">
                        合計: <strong style="font-size: 1.4rem; color: #2563eb;">¥{{ number_format($calculatedTotalPrice) }}</strong>
                    </div>
                    <a href="/checkout" role="button" style="width: auto; padding: 0.6rem 2rem; font-size: 1rem; font-weight: bold; margin: 0; text-decoration: none;">
                        購入手続きへ進む
                    </a>
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 2.5rem 1rem;">
                <p style="color: #64748b; font-size: 1.05rem; margin-bottom: 1.5rem;">カートに商品が入っていません。</p>
                <a href="/" role="button" class="outline" style="width: auto; display: inline-block; padding: 0.5rem 1.5rem; text-decoration: none;">商品一覧へ戻る</a>
            </div>
        @endif
    </article>
@endsection