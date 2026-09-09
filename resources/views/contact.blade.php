@extends('layouts.base')

@section('title', 'お問い合わせ')

@section('content')
    <section class="information-page contact-page" aria-labelledby="contact-heading">
        <header class="information-page-hero">
            <h1 id="contact-heading">お問い合わせ</h1>
            <p>商品やご注文について、ご不明な点がございましたらお気軽にお問い合わせください。</p>
        </header>

        <div class="contact-layout">
            <aside class="contact-information" aria-label="お問い合わせのご案内">
                <h2>お問い合わせの前に</h2>
                <p>よくあるご質問に回答が掲載されている場合があります。まずはご利用ガイドをご確認ください。</p>
                <a href="{{ route('guide', absolute: false) }}#faq">よくあるご質問を見る</a>

                <div class="contact-hours">
                    <span>受付時間</span>
                    <strong>平日 10:00–17:00</strong>
                    <small>土日祝・年末年始を除く</small>
                </div>
            </aside>

            <form class="contact-form" data-demo-form>
                <p class="contact-form-note"><span>必須</span>は入力必須項目です。</p>

                <div class="contact-field">
                    <label for="contact-name">お名前 <span>必須</span></label>
                    <input id="contact-name" name="name" type="text" autocomplete="name" placeholder="山田 太郎" required>
                </div>

                <div class="contact-field">
                    <label for="contact-email">メールアドレス <span>必須</span></label>
                    <input id="contact-email" name="email" type="email" autocomplete="email"
                        placeholder="example@email.com" required>
                </div>

                <div class="contact-field">
                    <label for="contact-category">お問い合わせ種別 <span>必須</span></label>
                    <select id="contact-category" name="category" required>
                        <option value="">選択してください</option>
                        <option value="product">商品について</option>
                        <option value="order">ご注文について</option>
                        <option value="delivery">配送について</option>
                        <option value="return">返品・交換について</option>
                        <option value="other">その他</option>
                    </select>
                </div>

                <div class="contact-field">
                    <label for="contact-order-number">注文番号 <small>ご注文済みの方のみ</small></label>
                    <input id="contact-order-number" name="order_number" type="text" inputmode="numeric"
                        placeholder="例：1024">
                </div>

                <div class="contact-field">
                    <label for="contact-message">お問い合わせ内容 <span>必須</span></label>
                    <textarea id="contact-message" name="message" rows="8" placeholder="お問い合わせ内容をご入力ください" required></textarea>
                </div>

                <label class="contact-consent">
                    <input name="privacy_agreement" type="checkbox" required>
                    <span><a href="{{ route('guide', absolute: false) }}#privacy-policy">プライバシーポリシー</a>に同意する</span>
                </label>

                <button type="button">入力内容を送信</button>
                <p class="contact-demo-note">※ このフォームは見本、送信機能はありません。</p>
            </form>
        </div>
    </section>
@endsection
