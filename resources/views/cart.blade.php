@extends('layouts.base')

@section('title', 'ショッピングカート')

@section('content')
    <article style="padding: 1.5rem;">
        <h2 style="margin-bottom: 1.5rem;">🛒 ショッピングカート</h2>

        <!-- メッセージ表示 -->
        @if (session('message'))
            <ins style="color: #16a34a; display: block; margin-bottom: 1.5rem; font-weight: bold;">{{ session('message') }}</ins>
        @endif

        @if (isset($cart) && count($cart) > 0)
            <table style="margin-bottom: 2rem;">
                <thead>
                    <tr>
                        <th>商品名</th>
                        <th style="text-align: right;">単価</th>
                        <th style="text-align: center; width: 100px;">数量</th>
                        <th style="text-align: right;">小計</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($cart as $id => $details)
                        @php $subtotal = $details['price'] * $details['quantity']; $total += $subtotal; @endphp
                        <tr>
                            <td>
                                <strong>{{ $details['name'] }}</strong>
                            </td>
                            <td style="text-align: right;">¥{{ number_format($details['price']) }}</td>
                            <td style="text-align: center;">{{ $details['quantity'] }}個</td>
                            <td style="text-align: right; font-weight: bold;">¥{{ number_format($subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- 合計金額 & アクションボタンエリア -->
            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 2px solid var(--pico-muted-border-color); padding-top: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                <form action="/cart/clear" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="secondary outline" style="width: auto; padding: 0.4rem 0.8rem; font-size: 0.85rem; margin: 0;">
                        カートを空にする
                    </button>
                </form>

                <div style="text-align: right; display: flex; align-items: center; gap: 1.5rem;">
                    <div style="font-size: 1.2rem;">
                        合計: <strong style="font-size: 1.5rem; color: #2563eb;">¥{{ number_format($total) }}</strong>
                    </div>
                    <form action="/orders" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" style="width: auto; padding: 0.6rem 2rem; font-size: 1.05rem; font-weight: bold; margin: 0;">
                            購入手続きへ進む
                        </button>
                    </form>
                </div>
            </div>
        @else
            <p>カートに商品が入っていません。</p>
            <a href="/" role="button" class="outline" style="width: auto; margin-top: 1rem;">商品一覧へ戻る</a>
        @endif
    </article>
@endsection