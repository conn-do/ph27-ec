@extends('layouts.base')

@section('title', 'マイページ')

@section('content')
<article style="max-width: 900px; margin: 0 auto; padding: 1.5rem 1rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="margin: 0;">マイページ</h2>
    </div>

    <!-- フラッシュメッセージ -->
    @if (session('message'))
        <ins style="color: #16a34a; display: block; margin-bottom: 1rem;">{{ session('message') }}</ins>
    @endif
    @if (session('error'))
        <del style="color: #dc2626; display: block; margin-bottom: 1rem;">{{ session('error') }}</del>
    @endif

    <!-- 🎁 保有ポイント表示カード -->
    <div style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #ffffff; padding: 1.2rem 1.5rem; border-radius: 12px; margin-bottom: 2rem; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <span style="font-size: 0.9rem; opacity: 0.9; display: block; margin-bottom: 0.2rem;">🎁 保有ポイント</span>
            <div style="display: flex; align-items: baseline; gap: 0.3rem;">
                <strong style="font-size: 2.2rem; font-weight: bold; line-height: 1;">
                    {{ number_format(auth()->user()->point ?? 0) }}
                </strong>
                <span style="font-size: 1.1rem; font-weight: bold;">pt</span>
            </div>
        </div>
        <small style="opacity: 0.85; font-size: 0.85rem;">※ 1pt = 1円としてお支払いにご利用いただけます。</small>
    </div>

    <!-- 📊 ポイント履歴セクション -->
    <section style="margin-bottom: 3rem;">
        <h3 style="font-size: 1.3rem; margin-bottom: 1rem; border-bottom: 2px solid var(--pico-muted-border-color); padding-bottom: 0.4rem;">
            📊 ポイント履歴
        </h3>

        @if (isset($pointHistories) && $pointHistories->count() > 0)
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <table style="width: 100%; border-collapse: collapse; margin: 0;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                            <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.85rem; color: #64748b;">日時</th>
                            <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.85rem; color: #64748b;">内容</th>
                            <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.85rem; color: #64748b;">増減pt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pointHistories as $history)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 0.75rem 1rem; font-size: 0.85rem; color: #64748b; white-space: nowrap;">
                                    {{ $history->created_at->format('Y/m/d H:i') }}
                                </td>
                                <td style="padding: 0.75rem 1rem; font-size: 0.9rem;">
                                    {{ $history->description }}
                                </td>
                                <td style="padding: 0.75rem 1rem; text-align: right; font-weight: bold; font-size: 0.95rem; color: {{ $history->points > 0 ? '#16a34a' : '#dc2626' }}; white-space: nowrap;">
                                    {{ $history->points > 0 ? '+' : '' }}{{ number_format($history->points) }} pt
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p style="color: #6b7280; font-size: 0.95rem;">ポイントの利用・獲得履歴はありません。</p>
        @endif
    </section>

    <!-- 📦 購入履歴セクション -->
    <section style="margin-bottom: 3rem;">
        <h3 style="font-size: 1.3rem; margin-bottom: 1rem; border-bottom: 2px solid var(--pico-muted-border-color); padding-bottom: 0.4rem;">
            📦 購入履歴
        </h3>

        @if (isset($orders) && $orders->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 1.2rem;">
                {{-- 最新3件のみ表示 --}}
                @foreach ($orders->take(3) as $order)
                    <article style="padding: 1.2rem; margin: 0; border-radius: 8px; background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.6rem; margin-bottom: 0.8rem; flex-wrap: wrap; gap: 0.5rem;">
                            <div>
                                <small style="color: #6b7280; display: block;">注文日</small>
                                <strong>{{ $order->created_at->format('Y/m/d H:i') }}</strong>
                            </div>
                            <div style="text-align: right;">
                                <small style="color: #6b7280; display: block;">お支払い合計</small>
                                <strong style="color: #2563eb; font-size: 1.05rem;">
                                    ¥{{ number_format($order->total_price ?? $order->total) }}
                                </strong>
                                @if (($order->used_point ?? 0) > 0)
                                    <div style="font-size: 0.75rem; color: #dc2626;">
                                        (ポイント利用: -{{ number_format($order->used_point) }}pt)
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                            @foreach ($order->details as $item)
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.8rem;">
                                    <div style="display: flex; align-items: center; gap: 0.8rem; flex-grow: 1;">
                                        @if (optional($item->product)->image)
                                            <img src="{{ $item->product->imageUrl() }}" alt="{{ $item->product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                                        @endif
                                        <div style="flex-grow: 1;">
                                            <h4 style="font-size: 0.95rem; margin: 0 0 0.2rem 0;">
                                                {{ optional($item->product)->name ?? '削除された商品' }}
                                            </h4>
                                            <span style="font-size: 0.85rem; color: #6b7280;">
                                                ¥{{ number_format($item->price ?? optional($item->product)->price ?? 0) }} × {{ $item->quantity }}個
                                            </span>
                                        </div>
                                    </div>

                                    {{-- 配達完了している場合のみ商品詳細ページへのリンクを表示 --}}
                                    @if ($order->status === 'delivered' && $item->product)
                                        <a href="/products/{{ $item->product->id }}" class="secondary outline" style="display: inline-block; padding: 0.35rem 0.75rem; font-size: 0.8rem; text-decoration: none; white-space: nowrap; margin: 0;">
                                            もう一度購入
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div style="margin-top: 1rem; text-align: right; border-top: 1px dashed #e5e7eb; padding-top: 0.8rem;">
                            <a href="{{ route('orders.tracking', $order->id) }}" style="display: inline-block; padding: 0.4rem 0.8rem; background: #2563eb; color: white; border-radius: 4px; text-decoration: none; font-size: 0.85rem;">
                                🚚 配送状況を確認する
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($orders->count() > 3)
                <div style="text-align: center; margin-top: 1.5rem;">
                    <a href="{{ route('orders.index') }}" class="secondary outline" style="display: inline-block; padding: 0.6rem 1.2rem; font-size: 0.9rem; text-decoration: none;">
                        過去の注文履歴を見る（全{{ $orders->count() }}件） →
                    </a>
                </div>
            @endif
        @else
            <p style="color: #6b7280; font-size: 0.95rem;">まだ購入履歴はありません。</p>
        @endif
    </section>

    <!-- お気に入り商品一覧エリア -->
    <section style="margin-bottom: 3rem;">
        <h3 style="font-size: 1.3rem; margin-bottom: 1rem; border-bottom: 2px solid var(--pico-muted-border-color); padding-bottom: 0.4rem;">♡ お気に入り商品</h3>
        @if (isset($favoriteProducts) && $favoriteProducts->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
                @foreach ($favoriteProducts as $favProduct)
                    <article style="padding: 1rem; margin: 0; display: flex; flex-direction: column; justify-content: space-between; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <div>
                            <a href="/products/{{ $favProduct->id }}" style="text-decoration: none;">
                                <img src="{{ $favProduct->imageUrl() }}" alt="{{ $favProduct->name }}" style="width: 100%; height: 140px; object-fit: cover; border-radius: 6px; margin-bottom: 0.5rem;">
                                <h4 style="font-size: 1rem; margin-bottom: 0.5rem; color: #111827;">{{ $favProduct->name }}</h4>
                            </a>
                            <p style="font-weight: bold; color: #2563eb; margin-bottom: 0.5rem;">¥{{ number_format($favProduct->price) }}</p>
                        </div>
                        <form action="{{ route('favorites.destroy', $favProduct->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="secondary outline" style="padding: 4px 8px; font-size: 0.8rem; width: 100%; margin: 0;">
                                削除
                            </button>
                        </form>
                    </article>
                @endforeach
            </div>
        @else
            <p style="color: #6b7280; font-size: 0.95rem;">お気に入りに登録した商品はまだありません。</p>
        @endif
    </section>

    <!-- 投稿レビュー一覧エリア -->
    <section style="margin-bottom: 3rem;">
        <h3 style="font-size: 1.3rem; margin-bottom: 1rem; border-bottom: 2px solid var(--pico-muted-border-color); padding-bottom: 0.4rem;">💬 投稿したレビュー</h3>
        @if (isset($reviews) && $reviews->count() > 0)
            <div style="display: grid; gap: 1rem;">
                @foreach ($reviews as $review)
                    <article style="padding: 1.2rem; margin: 0; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 10px;">
                            <div>
                                <h4 style="margin-bottom: 0.3rem;">
                                    @if ($review->product)
                                        <a href="/products/{{ $review->product->id }}">{{ $review->product->name }}</a>
                                    @else
                                        商品情報なし
                                    @endif
                                </h4>
                                <div style="color: #f59e0b; font-size: 1rem; margin-bottom: 0.5rem;">
                                    {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                </div>
                            </div>

                            <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('本当にこのレビューを削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="secondary outline" style="padding: 4px 12px; font-size: 0.85rem; margin: 0; color: #dc2626; border-color: #fca5a5;">
                                    レビューを削除
                                </button>
                            </form>
                        </div>

                        <p style="margin-bottom: 0.5rem; white-space: pre-wrap;">{{ $review->comment }}</p>
                        <small style="color: #666;">投稿日時: {{ $review->created_at->format('Y-m-d H:i') }}</small>
                    </article>
                @endforeach
            </div>
        @else
            <p style="color: #6b7280; font-size: 0.95rem;">まだ投稿したレビューはありません。</p>
        @endif
    </section>

    {{-- 管理者専用エリア --}}
    @if (auth()->user()->email === 'test@example.com')
        <article style="background-color: #f8fafc; border: 1px solid #cbd5e1; padding: 1.2rem; margin-bottom: 2rem; border-radius: 8px;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <strong style="font-size: 1.05rem; display: block; margin-bottom: 0.25rem;">⚙️ 管理者権限メニュー</strong>
                    <small style="color: #64748b;">商品の登録・編集や注文データの管理を行えます。</small>
                </div>
                <a href="/admin/orders" role="button" class="contrast" style="width: auto; margin: 0; padding: 0.5rem 1.2rem; font-weight: bold; text-decoration: none;">
                    管理画面へ進む →
                </a>
            </div>
        </article>
    @endif
</article>
@endsection