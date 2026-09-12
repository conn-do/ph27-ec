@extends('layouts.base')

@section('title', '注文管理（管理者画面）')

@section('content')
<article style="max-width: 900px; margin: 0 auto; padding: 1.5rem;">
    {{-- ヘッダー & ナビゲーション --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <h2 style="margin: 0; font-size: 1.5rem;">📦 注文・配送管理</h2>
        
        {{-- ナビゲーションボタン群 --}}
        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
            <a href="/admin/orders" class="primary" style="font-size: 0.85rem; padding: 0.4rem 0.8rem; width: auto; margin: 0; text-decoration: none;">
                📦 注文・配送管理
            </a>
            <a href="/admin/sales" class="secondary outline" style="font-size: 0.85rem; padding: 0.4rem 0.8rem; width: auto; margin: 0; text-decoration: none;">
                🏷️ セール価格管理へ
            </a>
            <a href="/mypage" class="secondary outline" style="font-size: 0.85rem; padding: 0.4rem 0.8rem; width: auto; margin: 0; text-decoration: none;">
                ← マイページへ戻る
            </a>
        </div>
    </div>

    @if (session('message'))
        <ins style="color: #16a34a; display: block; margin-bottom: 1rem;">{{ session('message') }}</ins>
    @endif

    @if (isset($orders) && $orders->count() > 0)
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            @foreach ($orders as $order)
                <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.8rem; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                        <div>
                            <strong>注文番号: #{{ $order->id }}</strong>
                            <small style="color: #6b7280; display: block;">注文者: {{ optional($order->user)->name ?? '不明' }} ({{ $order->created_at->format('Y/m/d H:i') }})</small>
                        </div>
                        <div style="text-align: right;">
                            <small style="color: #6b7280;">請求金額（決済額）</small>
                            <div style="color: #2563eb; font-weight: bold; font-size: 1.1rem;">¥{{ number_format($order->total_price) }}</div>
                            @if (($order->used_point ?? 0) > 0)
                                <span style="font-size: 0.8rem; color: #dc2626; background: #fef2f2; padding: 0.1rem 0.4rem; border-radius: 4px; display: inline-block; margin-top: 0.2rem;">
                                    🎁 利用pt: -{{ number_format($order->used_point) }}pt
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- 配送ステータス変更フォーム --}}
                    <div style="background: #f8fafc; padding: 0.8rem 1rem; border-radius: 6px; margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
                        <span style="font-size: 0.9rem; font-weight: bold; color: #475569;">🚚 配送ステータス変更：</span>
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" style="display: flex; align-items: center; gap: 0.5rem; margin: 0;">
                            @csrf
                            @method('PATCH')
                            
                            {{-- プルダウンのかぶりを修正するスタイル --}}
                            <div style="position: relative; display: inline-block;">
                                <select name="status" style="
                                    margin: 0;
                                    padding: 0.4rem 2.5rem 0.4rem 0.8rem;
                                    min-width: 150px;
                                    font-size: 0.9rem;
                                    width: auto;
                                    line-height: 1.4;
                                    background-position: right 0.75rem center;
                                    background-size: 0.8rem;
                                ">
                                    <option value="ordered" {{ $order->status === 'ordered' ? 'selected' : '' }}>注文受付</option>
                                    <option value="shipping_preparing" {{ $order->status === 'shipping_preparing' ? 'selected' : '' }}>発送準備中</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>発送済み</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>注文完了</option>
                                </select>
                            </div>

                            <button type="submit" style="margin: 0; padding: 0.4rem 1rem; width: auto; font-size: 0.9rem;">更新</button>
                        </form>
                    </div>

                    {{-- 注文商品一覧 --}}
                    <div style="font-size: 0.9rem; color: #334155;">
                        <strong>注文内訳:</strong>
                        <ul style="margin: 0.5rem 0 0 1.2rem; padding: 0;">
                            @foreach ($order->details as $detail)
                                <li>
                                    {{ optional($detail->product)->name ?? '削除された商品' }} 
                                    × {{ $detail->quantity }}個 
                                    (¥{{ number_format(($detail->price ?? optional($detail->product)->price ?? 0) * $detail->quantity) }})
                                </li>
                            @endforeach

                            @if (($order->gift_option ?? '') === 'box')
                                <li style="color: #d97706; list-style-type: disc;">
                                    🎁 ギフトBOX＆メッセージカード: +¥300
                                </li>
                            @elseif (in_array($order->gift_option ?? '', ['ribbon', 'free']))
                                <li style="color: #16a34a; list-style-type: disc;">
                                    🎀 簡易リボンラッピング: ¥0
                                </li>
                            @endif

                            @if (($order->used_point ?? 0) > 0)
                                <li style="color: #dc2626; list-style-type: disc; font-weight: bold;">
                                    🎁 ポイント値引き: -¥{{ number_format($order->used_point) }} ({{ number_format($order->used_point) }}pt利用)
                                </li>
                            @endif
                        </ul>
                    </div>
                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" style="margin: 0;" onsubmit="return confirm('この注文データを完全に削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="secondary outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem; color: #dc2626; border-color: #fca5a5; margin: 0;">
                            🗑️ 注文を削除
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <p style="color: #6b7280;">注文はまだありません。</p>
    @endif
</article>
@endsection