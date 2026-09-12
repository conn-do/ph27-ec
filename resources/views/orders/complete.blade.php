@extends('layouts.base')

@section('title', '注文完了')

@section('content')
    <div style="max-width: 600px; margin: 2rem auto;">
        <article style="padding: 2.5rem 2rem; text-align: center; background: #fff; border: 1px solid var(--pico-muted-border-color); border-radius: 8px;">
            <div style="font-size: 3.5rem; margin-bottom: 0.5rem;">🎉</div>
            <h2 style="color: #16a34a; margin-bottom: 1rem;">ご注文が完了いたしました！</h2>
            
            @if (isset($order) && $order)
                <p style="font-size: 1.1rem; color: #4b5563; margin-bottom: 1.5rem;">
                    注文ID: <strong>#{{ $order->id }}</strong>
                </p>
            @endif

            <p style="line-height: 1.6; color: #6b7280; margin-bottom: 2rem;">
                ご購入いただきありがとうございます。<br>
                商品の発送準備が整い次第、順次発送いたします。
            </p>

            {{-- ナビゲーションボタンエリア --}}
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="/" role="button" style="width: auto; padding: 0.6rem 1.8rem; font-weight: bold; text-decoration: none;">
                    🏠 トップへ戻る
                </a>
                <a href="/mypage" role="button" class="outline secondary" style="width: auto; padding: 0.6rem 1.8rem; text-decoration: none;">
                    👤 マイページを見る
                </a>
            </div>
        </article>
    </div>
@endsection