@extends('layouts.base')

@section('title', 'よくあるご質問')

@section('content')
    <div class="max-w-2xl">
        <h1 class="mb-6 text-xl font-bold text-stone-900">よくあるご質問</h1>

        <div class="space-y-4">
            @foreach ([
                '会員登録は必要ですか？' => '商品の閲覧・カートへの追加は会員登録なしでもご利用いただけます。ご購入手続き、レビュー投稿、お気に入り登録には会員登録（無料）が必要です。',
                'どのような支払い方法が使えますか？' => 'クレジットカード決済（Stripe）に対応しています。',
                '配送にはどのくらいかかりますか？' => 'ご注文確認後、通常3〜5営業日以内に発送いたします。発送が完了すると、配送業者・追跡番号を記載したメールをお送りします。',
                '注文をキャンセルできますか？' => '発送前の注文であれば、マイページの注文詳細画面からキャンセルが可能です。発送済みの注文はキャンセルできません。',
                'クーポンはどこで使えますか？' => 'カート画面のクーポン入力欄にコードを入力し「適用する」を押すと、合計金額に割引が反映されます。',
                '在庫がない商品は購入できますか？' => '在庫が0の商品はカートに追加できません。再入荷まで今しばらくお待ちください。',
            ] as $question => $answer)
                <div class="rounded-xl border border-stone-200 bg-white p-5">
                    <h2 class="mb-2 text-sm font-bold text-stone-900">Q. {{ $question }}</h2>
                    <p class="text-sm leading-relaxed text-stone-600">A. {{ $answer }}</p>
                </div>
            @endforeach
        </div>

        <p class="mt-6 text-sm text-stone-500">
            解決しない場合は<a href="/contact" class="text-amber-700 hover:underline">お問い合わせフォーム</a>よりご連絡ください。
        </p>
    </div>
@endsection
