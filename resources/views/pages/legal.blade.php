@extends('layouts.base')

@section('title', '特定商取引法に基づく表記')

@section('content')
    <div class="max-w-2xl">
        <h1 class="mb-6 text-xl font-bold text-stone-900">特定商取引法に基づく表記</h1>

        <div class="overflow-hidden rounded-xl border border-stone-200 bg-white">
            <dl class="divide-y divide-stone-100">
                @foreach ([
                    '販売業者' => 'すごい文房具サイト運営事務局',
                    '運営統括責任者' => '山田 太郎',
                    '所在地' => '東京都新宿区西新宿1-1-1',
                    '電話番号' => '03-1234-5678（平日10:00〜17:00）',
                    'メールアドレス' => 'support@example.com',
                    '販売価格' => '各商品ページに表示する価格（税込）',
                    '商品代金以外の必要料金' => '送料、消費税（商品価格に含む）',
                    'お支払い方法' => 'クレジットカード決済（Stripe）',
                    'お支払い時期' => 'ご注文時にお支払いが確定します',
                    '商品の引渡時期' => 'ご注文確認後、通常3〜5営業日以内に発送',
                    '返品・交換について' => '商品到着後7日以内、未使用の場合に限り承ります。お問い合わせフォームよりご連絡ください',
                ] as $label => $value)
                    <div class="grid grid-cols-1 gap-1 px-5 py-4 text-sm sm:grid-cols-3 sm:gap-4">
                        <dt class="font-medium text-stone-500">{{ $label }}</dt>
                        <dd class="text-stone-800 sm:col-span-2">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </div>
@endsection
