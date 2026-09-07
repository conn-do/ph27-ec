@extends('layouts.base')

@section('title', 'ショッピングカート')

@section('content')
    <article style="padding: 1.5rem;">
        <h2 style="margin-bottom: 1.5rem;">🛒 ショッピングカート</h2>

        <!-- メッセージ表示 -->
        @if (session('message'))
            <ins style="color: #16a34a; display: block; margin-bottom: 1.5rem; font-weight: bold;">{{ session('message') }}</ins>
        @endif

        @if (isset($items) && count($items) > 0)
            <table style="margin-bottom: 2rem;">
                <thead>
                    <tr>
                        <th>商品</th>
                        <th style="text-align: right;">単価</th>
                        <th style="text-align: center; width: 100px;">数量</th>
                        <th style="text-align: right;">小計</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        @php $subtotal = $item['product']->price * $item['quantity']; @endphp
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.8rem;">
                                    @if ($item['product']->image)
                                        <img src="{{ $item['product']->imageUrl() }}" alt="{{ $item['product']->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                    @endif
                                    <strong>{{ $item['product']->name }}</strong>
                                </div>
                            </td>
                            <td style="text-align: right;">¥{{ number_format($item['product']->price) }}</td>
                            <td style="text-align: center;">{{ $item['quantity'] }}個</td>
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
                        合計: <strong style="font-size: 1.5rem; color: #2563eb;">¥{{ number_format($totalPrice) }}</strong>
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