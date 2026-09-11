@extends('layouts.base')

@section('title', 'プライバシーポリシー')

@section('content')
    <div class="max-w-2xl">
        <h1 class="mb-6 text-xl font-bold text-stone-900">プライバシーポリシー</h1>

        <div class="space-y-6 rounded-xl border border-stone-200 bg-white p-6 text-sm leading-relaxed text-stone-700">
            <section>
                <h2 class="mb-2 text-base font-bold text-stone-900">1. 個人情報の取得</h2>
                <p>当サイトは、会員登録・お問い合わせ・商品のご注文の際に、お名前・メールアドレス・配送先住所・電話番号などの個人情報を取得します。</p>
            </section>

            <section>
                <h2 class="mb-2 text-base font-bold text-stone-900">2. 個人情報の利用目的</h2>
                <p>取得した個人情報は、商品の発送、注文確認・発送通知のご連絡、お問い合わせへの対応、サービス向上のための分析のために利用します。</p>
            </section>

            <section>
                <h2 class="mb-2 text-base font-bold text-stone-900">3. 個人情報の第三者提供</h2>
                <p>法令に基づく場合を除き、お客様の同意なく個人情報を第三者に提供することはありません。ただし、商品配送のため配送業者に必要な情報を共有する場合があります。</p>
            </section>

            <section>
                <h2 class="mb-2 text-base font-bold text-stone-900">4. 決済情報の取り扱い</h2>
                <p>クレジットカード情報は決済代行会社（Stripe）を通じて処理され、当サイトのサーバーには保存されません。</p>
            </section>

            <section>
                <h2 class="mb-2 text-base font-bold text-stone-900">5. 個人情報の開示・訂正・削除</h2>
                <p>お客様ご本人からの個人情報の開示・訂正・削除のご希望には、お問い合わせフォームより承ります。</p>
            </section>
        </div>
    </div>
@endsection
