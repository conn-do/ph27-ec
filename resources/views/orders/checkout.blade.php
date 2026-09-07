@extends('layouts.base')
@section('title', 'ご注文手続き')
@section('content')
    <div class="page-heading">
        <p class="eyebrow">
            CHECKOUT
        </p>
        <h1>
            ご注文手続き
        </h1>
        <p>
            お届け先とご注文内容をご確認ください。
        </p>
    </div>
    <form action="{{ route('orders.store') }}" method="post" class="purchase-layout" data-submit>
        @csrf
        <input type="hidden" name="checkout_token" value="{{ session('checkout_token') }}">
        <section class="checkout-fields">
            <h2>
                01 / お届け先
            </h2>
            <p class="muted">
                すべて必須項目です。デモ用の架空の住所でお試しください。
            </p>
            @foreach(['recipient_name' => ['お名前', '山田 太郎', 'name', 100], 'postal_code' => ['郵便番号', '123-4567', 'postal-code', 8], 'address' => ['住所', '東京都新宿区西新宿1-1-1 ○○マンション101', 'street-address', 255], 'phone' => ['電話番号', '090-1234-5678', 'tel', 20]] as $field => [$label, $placeholder, $autocomplete, $length])
                <div class="form-field">
                    <label for="{{ $field }}">
                        {{ $label }}
                        <span>
                            必須
                        </span>
                    </label>
                    <input type="{{ $field === 'phone' ? 'tel' : 'text' }}" id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $field === 'recipient_name' ? auth()->user()->name : '') }}" placeholder="{{ $placeholder }}" autocomplete="{{ $autocomplete }}" maxlength="{{ $length }}" @if($field === 'postal_code') pattern="[0-9]{3}-?[0-9]{4}" inputmode="numeric" @endif required @error($field) aria-invalid="true" aria-describedby="{{ $field }}-error" @enderror>
                    @error($field)
                        <p class="field-error" id="{{ $field }}-error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            @endforeach
            <h2>
                02 / お支払い
            </h2>
            <div class="payment-note">
                <strong>
                    デモ注文（お支払い不要）
                </strong>
                <p>
                    クレジットカード情報は入力しないでください。
                    <br>
                    注文内容の保存と在庫の更新を体験できます。
                </p>
            </div>
            <a href="{{ route('cart.index') }}">
                ← カートを編集する
            </a>
        </section>
        <aside class="order-summary">
            <h2>
                ご注文の確認
            </h2>
            <ul class="checkout-items">
                @foreach($items as $item)
                    <li>
                        <span>
                            {{ $item['product']->name }} × {{ $item['quantity'] }}
                        </span>
                        <strong>
                            ¥{{ number_format($item['product']->price * $item['quantity']) }}
                        </strong>
                    </li>
                @endforeach
            </ul>
            <dl>
                <div>
                    <dt>
                        商品小計
                    </dt>
                    <dd>
                        ¥{{ number_format($subtotal) }}
                    </dd>
                </div>
                <div>
                    <dt>
                        送料
                    </dt>
                    <dd>
                        {{ $shipping === 0 ? '無料' : '¥'.number_format($shipping) }}
                    </dd>
                </div>
                <div class="total">
                    <dt>
                        合計（税込）
                    </dt>
                    <dd>
                        ¥{{ number_format($totalPrice) }}
                    </dd>
                </div>
            </dl>
            <button class="button full-width" type="submit">
                デモ注文を確定する →
            </button>
            <p class="demo-note">
                実際のお支払い・配送は発生しません。
            </p>
        </aside>
    </form>
@endsection
