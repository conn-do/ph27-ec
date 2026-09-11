@extends('layouts.base')

@section('title', 'ショッピングカート')

@section('content')

<div style="max-width: 800px; margin: 40px auto; color: #e5e7eb; padding: 0 20px;">

    <h2 style="font-size: 20px; font-weight: 600; color: #e5e7eb; margin-top: 0; margin-bottom: 30px; border-left: 4px solid #38bdf8; padding-left: 12px;">
        ショッピングカート
    </h2>

    {{-- フラッシュメッセージ --}}
    @if(session('message'))
        <div style="background-color: #065f46; color: #a7f3d0; padding: 10px 14px; font-size: 13px; border-radius: 4px; margin-bottom: 20px;">
            {{ session('message') }}
        </div>
    @endif

    {{-- カートに商品が入っている場合 --}}
    @if(isset($items) && count($items) > 0)
        <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 40px;">
            @foreach($items as $item)
                <div style="display: flex; justify-content: space-between; align-items: center; background: #13161E; border: 1px solid #2a3245; padding: 20px; border-radius: 4px;">
                    <div>
                        <div style="font-size: 15px; font-weight: 600; color: #fff; margin-bottom: 6px;">
                            {{ $item['product']->name ?? '商品名' }}
                        </div>
                        <div style="font-size: 13px; color: #38bdf8;">
                            ¥{{ number_format($item['product']->price ?? 0) }}
                        </div>
                    </div>
                    <div style="font-size: 14px; color: #9ca3af;">
                        数量: {{ $item['quantity'] }}
                    </div>
                </div>
            @endforeach
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; background: #13161E; border: 1px solid #2a3245; padding: 24px; border-radius: 4px; margin-bottom: 30px;">
            <span style="font-size: 15px; color: #9ca3af;">合計金額</span>
            <span style="font-size: 20px; font-weight: 600; color: #38bdf8;">¥{{ number_format($totalPrice ?? 0) }}</span>
        </div>

        <div style="display: flex; gap: 15px; align-items: center;">
            <form action="/orders" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" style="padding: 10px 24px; background: transparent; color: #38bdf8; border: 1px solid #38bdf8; font-size: 14px; cursor: pointer; border-radius: 4px; transition: all 0.2s;">
                    レジに進む
                </button>
            </form>
            <a href="/cart/clear" style="font-size: 13px; color: #f87171; text-decoration: none;">
                カートを空にする
            </a>
        </div>

    {{-- カートが空の場合 --}}
    @else
        <div style="text-align: center; padding: 60px 0; color: #9ca3af; font-size: 14px;">
            <p style="margin-bottom: 25px;">カートに商品は入っていません。</p>
            <a href="/" style="display: inline-block; padding: 10px 24px; background: transparent; color: #38bdf8; border: 1px solid #38bdf8; font-size: 14px; text-decoration: none; border-radius: 4px;">
                買い物を続ける
            </a>
        </div>
    @endif

</div>

@endsection