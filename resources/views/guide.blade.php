@extends('layouts.base')

@section('title', 'ご利用ガイド')

@section('content')
    <section class="information-page" aria-labelledby="guide-heading">
        <header class="information-page-hero">
            <h1 id="guide-heading">ご利用ガイド</h1>
            <p>はじめてのお買い物も、いつものご注文も。ご利用に必要な情報をまとめています。</p>
        </header>

        <nav class="guide-index" aria-label="ご利用ガイドの目次">
            <a href="#order">ご注文の流れ</a>
            <a href="#payment">お支払い</a>
            <a href="#delivery">配送・送料</a>
            <a href="#returns">返品・交換</a>
            <a href="#faq">よくあるご質問</a>
        </nav>

        <div class="guide-sections">
            <section id="order" class="guide-section" aria-labelledby="order-heading">
                <div class="guide-section-heading">
                    <span>01</span>
                    <h2 id="order-heading">ご注文の流れ</h2>
                </div>
                <ol class="guide-steps">
                    <li>
                        <strong>商品を選ぶ</strong>
                        <p>商品ページで数量を選び、カートに追加します。</p>
                    </li>
                    <li>
                        <strong>ご注文内容を確認</strong>
                        <p>カートで商品、数量、金額をご確認ください。</p>
                    </li>
                    <li>
                        <strong>お届け先・お支払い方法を入力</strong>
                        <p>ログイン後、チェックアウト画面で必要事項を入力します。</p>
                    </li>
                    <li>
                        <strong>ご注文完了</strong>
                        <p>注文履歴はマイページからいつでも確認できます。</p>
                    </li>
                </ol>
            </section>

            <section id="payment" class="guide-section" aria-labelledby="payment-heading">
                <div class="guide-section-heading">
                    <span>02</span>
                    <h2 id="payment-heading">お支払いについて</h2>
                </div>
                <div class="guide-copy">
                    <p>クレジットカード、スマホ決済、コンビニ払い、銀行振込からお選びいただけます。</p>
                    <dl class="guide-definition-list">
                        <div>
                            <dt>クレジットカード</dt>
                            <dd>Visa / Mastercard / JCB / American Express などに対応しています。</dd>
                        </div>
                        <div>
                            <dt>スマホ決済</dt>
                            <dd>PayPayアプリで表示される案内に沿ってお支払いください。</dd>
                        </div>
                        <div>
                            <dt>コンビニ払い</dt>
                            <dd>セブン-イレブン、ローソン、ファミリーマートでお支払いいただけます。</dd>
                        </div>
                        <div>
                            <dt>銀行振込</dt>
                            <dd>ご注文後に表示される振込先へ、7日以内にお振り込みください。</dd>
                        </div>
                    </dl>
                </div>
            </section>

            <section id="delivery" class="guide-section" aria-labelledby="delivery-heading">
                <div class="guide-section-heading">
                    <span>03</span>
                    <h2 id="delivery-heading">配送・送料について</h2>
                </div>
                <div class="guide-copy">
                    <p>通常、ご注文確定後3〜5営業日以内に発送します。発送後のお届け日数は地域により異なります。</p>
                    <div class="guide-highlight">
                        <span>全国一律送料</span>
                        <strong>¥550</strong>
                        <small>税込5,000円以上のご注文で送料無料</small>
                    </div>
                </div>
            </section>

            <section id="returns" class="guide-section" aria-labelledby="returns-heading">
                <div class="guide-section-heading">
                    <span>04</span>
                    <h2 id="returns-heading">返品・交換について</h2>
                </div>
                <div class="guide-copy">
                    <p>商品到着後7日以内にお問い合わせください。未使用品に限り、返品・交換を承ります。</p>
                    <p>破損・誤配送の場合は当店が送料を負担します。お客様都合の場合は返送料をご負担ください。</p>
                </div>
            </section>

            <section id="faq" class="guide-section" aria-labelledby="faq-heading">
                <div class="guide-section-heading">
                    <span>05</span>
                    <h2 id="faq-heading">よくあるご質問</h2>
                </div>
                <div class="guide-faq-list">
                    <details>
                        <summary><span>注文内容を変更できますか？</span><span class="guide-faq-icon" aria-hidden="true"></span></summary>
                        <p>発送準備前であれば対応できる場合があります。お問い合わせページからご連絡ください。</p>
                    </details>
                    <details>
                        <summary><span>領収書は発行できますか？</span><span class="guide-faq-icon" aria-hidden="true"></span></summary>
                        <p>注文履歴の詳細画面から、ご注文内容をご確認いただけます。</p>
                    </details>
                    <details>
                        <summary><span>会員登録をしなくても購入できますか？</span><span class="guide-faq-icon" aria-hidden="true"></span>
                        </summary>
                        <p>配送先や注文履歴を安全に管理するため、ご購入には会員登録とログインが必要です。</p>
                    </details>
                </div>
            </section>

            <section id="privacy-policy" class="guide-section guide-policy" aria-labelledby="privacy-heading">
                <div class="guide-section-heading">
                    <span>06</span>
                    <h2 id="privacy-heading">プライバシーポリシー</h2>
                </div>
                <div class="guide-copy">
                    <p>当店は、ご注文の受付、商品の発送、お問い合わせへの対応など、サービスの提供に必要な範囲でお客様の個人情報を利用します。</p>
                    <p>法令に基づく場合を除き、ご本人の同意なく個人情報を第三者へ提供しません。お預かりした情報は適切な安全管理措置のもとで取り扱います。</p>
                </div>
            </section>

            <section id="legal-notice" class="guide-section guide-policy" aria-labelledby="legal-heading">
                <div class="guide-section-heading">
                    <span>07</span>
                    <h2 id="legal-heading">特定商取引法に基づく表記</h2>
                </div>
                <dl class="guide-legal-list">
                    <div>
                        <dt>販売事業者</dt>
                        <dd>Sugoi Stationery</dd>
                    </div>
                    <div>
                        <dt>販売価格</dt>
                        <dd>各商品ページに税込価格を表示</dd>
                    </div>
                    <div>
                        <dt>商品代金以外の料金</dt>
                        <dd>送料、振込手数料</dd>
                    </div>
                    <div>
                        <dt>商品の引渡時期</dt>
                        <dd>ご注文確定後、通常3〜5営業日以内に発送</dd>
                    </div>
                    <div>
                        <dt>返品・交換</dt>
                        <dd>商品到着後7日以内にご連絡ください</dd>
                    </div>
                    <div>
                        <dt>お問い合わせ</dt>
                        <dd><a href="{{ route('contact', absolute: false) }}">お問い合わせフォーム</a>よりご連絡ください</dd>
                    </div>
                </dl>
            </section>
        </div>
    </section>
@endsection
