@extends('layouts.base')

@section('title', '購入手続き')

@section('content')
    <section class="checkout-page" aria-labelledby="checkout-heading">
        <div class="page-heading">
            <h1 id="checkout-heading">購入手続き</h1>
            <a href="{{ route('cart.index') }}">カートに戻る</a>
        </div>

        <form class="checkout-form" action="{{ route('orders.store') }}" method="POST" data-postal-lookup
            data-postal-lookup-url="{{ route('postal-code.show', ['postalCode' => '__POSTAL_CODE__']) }}">
            @csrf

            <section class="checkout-section" aria-labelledby="shipping-heading">
                <h2 id="shipping-heading">配送先</h2>

                <div class="store-form-field">
                    <label for="shipping-name">お名前</label>
                    <input id="shipping-name" type="text" name="shipping_name"
                        value="{{ old('shipping_name', auth()->user()->delivery_name ?? auth()->user()->name) }}"
                        autocomplete="shipping name" required>
                    @error('shipping_name')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="store-form-field">
                    <label for="shipping-postal-code">郵便番号</label>
                    <input id="shipping-postal-code" type="text" name="shipping_postal_code"
                        value="{{ old('shipping_postal_code', auth()->user()->postal_code) }}"
                        autocomplete="shipping postal-code" inputmode="numeric" placeholder="123-4567"
                        data-postal-code required>
                    @error('shipping_postal_code')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                    <small class="postal-lookup-status" data-postal-status aria-live="polite"></small>
                </div>

                <div class="store-form-field">
                    <label for="shipping-address">住所</label>
                    <input id="shipping-address" type="text" name="shipping_address"
                        value="{{ old('shipping_address', auth()->user()->address) }}"
                        autocomplete="shipping street-address" data-postal-address required>
                    @error('shipping_address')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="store-form-field">
                    <label for="shipping-phone">電話番号</label>
                    <input id="shipping-phone" type="tel" name="shipping_phone"
                        value="{{ old('shipping_phone', auth()->user()->phone) }}" autocomplete="shipping tel"
                        inputmode="tel" required>
                    @error('shipping_phone')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

            </section>

            <section class="checkout-section checkout-payment" aria-labelledby="payment-heading">
                <div class="checkout-payment-heading">
                    <div>
                        <h2 id="payment-heading">お支払い方法</h2>
                        <p>ご希望のお支払い方法を選択してください。</p>
                    </div>
                    <span class="payment-security-note">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M7 10V7a5 5 0 0 1 10 0v3" />
                            <rect x="5" y="10" width="14" height="11" rx="2" />
                        </svg>
                        カード情報は保存されません
                    </span>
                </div>

                <fieldset class="payment-options" data-payment-options>
                    <legend class="visually-hidden">お支払い方法を選択</legend>
                    @foreach ($paymentMethods as $paymentMethod)
                        <div class="payment-option" data-payment-option
                            data-payment-method="{{ $paymentMethod->value }}">
                            <label class="payment-option-header" for="payment-{{ $paymentMethod->value }}">
                                <span class="payment-option-name">
                                    <input id="payment-{{ $paymentMethod->value }}" type="radio" name="payment_method"
                                        value="{{ $paymentMethod->value }}"
                                        @checked(old('payment_method', $paymentMethods[0]->value) === $paymentMethod->value)>
                                    <span class="payment-option-radio" aria-hidden="true"></span>
                                    <span>{{ $paymentMethod->label() }}</span>
                                </span>
                                <span class="payment-option-badges">
                                    @if ($paymentMethod === \App\PaymentMethod::CreditCard)
                                        <img src="{{ asset('images/payment/visa.webp') }}" alt="Visa">
                                        <img src="{{ asset('images/payment/mastercard.webp') }}" alt="Mastercard">
                                        <img src="{{ asset('images/payment/jcb.webp') }}" alt="JCB">
                                        <img src="{{ asset('images/payment/american-express.webp') }}"
                                            alt="American Express">
                                        <img src="{{ asset('images/payment/diners.png') }}" alt="Diners Club">
                                    @elseif ($paymentMethod === \App\PaymentMethod::MobilePayment)
                                        <img src="{{ asset('images/payment/paypay.webp') }}" alt="PayPay">
                                    @elseif ($paymentMethod === \App\PaymentMethod::ConvenienceStore)
                                        <img src="{{ asset('images/payment/famima.png') }}" alt="FamilyMart">
                                        <img src="{{ asset('images/payment/lawson.png') }}" alt="LAWSON">
                                        <img src="{{ asset('images/payment/seven.png') }}" alt="7-Eleven">
                                    @endif
                                </span>
                            </label>

                            @if ($paymentMethod === \App\PaymentMethod::CreditCard)
                                <div class="payment-card-fields" data-payment-panel="{{ $paymentMethod->value }}"
                                    @if (old('payment_method', $paymentMethods[0]->value) !== $paymentMethod->value) hidden @endif>
                                    <div class="payment-card-field payment-card-field-wide">
                                        <label for="card-number">カード番号</label>
                                        <div class="payment-card-input">
                                            <input id="card-number" type="text" inputmode="numeric"
                                                autocomplete="cc-number" placeholder="1234 5678 9012 3456"
                                                maxlength="19" data-card-number>
                                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M7 10V7a5 5 0 0 1 10 0v3" />
                                                <rect x="5" y="10" width="14" height="11" rx="2" />
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="payment-card-field">
                                        <label for="card-expiry">有効期限（月／年）</label>
                                        <input id="card-expiry" type="text" inputmode="numeric" autocomplete="cc-exp"
                                            placeholder="MM / YY" maxlength="7" data-card-expiry>
                                    </div>

                                    <div class="payment-card-field">
                                        <label for="card-security-code">セキュリティコード</label>
                                        <input id="card-security-code" type="text" inputmode="numeric"
                                            autocomplete="cc-csc" placeholder="123" maxlength="4" data-card-security-code>
                                    </div>

                                    <div class="payment-card-field payment-card-field-wide">
                                        <label for="card-holder">カードの名義人</label>
                                        <input id="card-holder" type="text" autocomplete="cc-name"
                                            placeholder="TARO YAMADA">
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </fieldset>
                @error('payment_method')
                    <small class="form-error payment-method-error">{{ $message }}</small>
                @enderror
            </section>

            <section class="checkout-section checkout-order" aria-labelledby="checkout-order-heading">
                <h2 id="checkout-order-heading">ご注文内容</h2>

                <div class="checkout-items">
                    @foreach ($items as $item)
                        <div class="checkout-item">
                            <img src="{{ $item['product']->imageUrl() }}" alt="{{ $item['product']->name }}">
                            <div>
                                <h3>{{ $item['product']->name }}</h3>
                                <span>数量：{{ $item['quantity'] }}点</span>
                            </div>
                            <strong>¥{{ number_format($item['subtotal']) }}</strong>
                        </div>
                    @endforeach
                </div>

                <div class="checkout-total">
                    <span>合計</span>
                    <strong>¥{{ number_format($totalPrice) }}</strong>
                </div>
            </section>

            <button class="store-primary-button checkout-submit" type="submit">注文を確定する</button>
        </form>
    </section>
@endsection
