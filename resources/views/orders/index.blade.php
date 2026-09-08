@extends('layouts.base')

@section('title', '注文完了')

@section('content')
    <div style="max-width: 600px; margin: 2rem auto;">
        <article style="padding: 2rem; text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">🎉</div>
            <h2 style="color: #16a34a; margin-bottom: 1rem;">ご注文が完了しました！</h2>
            
            @if (isset($order))
                <p style="font-size: 1.1rem; color: #4b5563; margin-bottom: 1.5rem;">
                    注文ID: <strong>#{{ $order->id }}</strong>
                </p>
            @endif

            <p style="line-height: 1.6; color: #6b7280; margin-bottom: 2rem;">
                ご購入いただきありがとうございます。<br>
                商品の発送準備が整い次第、順次配送いたします。
            </p>

            <div style="display: flex; gap: 1rem; justify-content: center;">
                <a href="/" role="button" style="width: auto; padding: 0.5rem 1.5rem;">トップページへ戻る</a>
                <a href="/mypage" role="button" class="outline" style="width: auto; padding: 0.5rem 1.5rem;">マイページを見る</a>
            </div>
        </article>
    </div>
@endsection