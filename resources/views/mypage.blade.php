@extends('layouts.base')

@section('title', 'マイページ')

@section('content')
    <section class="mypage" aria-labelledby="mypage-heading">
        <h1 id="mypage-heading">マイページ</h1>

        @if (session('message'))
            <p class="store-message">{{ session('message') }}</p>
        @endif

        <div class="mypage-user">
            <p class="mypage-user-name">{{ auth()->user()->name }} 様</p>
            <p class="mypage-user-email">{{ auth()->user()->email }}</p>
        </div>

        <details class="mypage-delivery">
            <summary>
                <h2 id="delivery-heading" class="mypage-delivery-heading">
                    <span>配送先情報</span>
                    <span class="mypage-delivery-action">
                        確認・変更
                        <svg viewBox="0 0 16 16" aria-hidden="true">
                            <path d="m4 6 4 4 4-4" />
                        </svg>
                    </span>
                </h2>
            </summary>

            <form class="delivery-form" action="{{ route('mypage.delivery-address.update') }}" method="POST"
                data-postal-lookup
                data-postal-lookup-url="{{ route('postal-code.show', ['postalCode' => '__POSTAL_CODE__']) }}">
                @csrf
                @method('PATCH')

                <div class="store-form-field">
                    <label for="delivery-name">お名前</label>
                    <input id="delivery-name" type="text" name="delivery_name"
                        value="{{ old('delivery_name', auth()->user()->delivery_name ?? auth()->user()->name) }}"
                        autocomplete="shipping name" required>
                    @error('delivery_name')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="store-form-field">
                    <label for="postal-code">郵便番号</label>
                    <input id="postal-code" type="text" name="postal_code"
                        value="{{ old('postal_code', auth()->user()->postal_code) }}" autocomplete="postal-code"
                        inputmode="numeric" placeholder="123-4567" data-postal-code required>
                    @error('postal_code')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                    <small class="postal-lookup-status" data-postal-status aria-live="polite"></small>
                </div>

                <div class="store-form-field">
                    <label for="address">住所</label>
                    <input id="address" type="text" name="address"
                        value="{{ old('address', auth()->user()->address) }}" autocomplete="street-address"
                        data-postal-address required>
                    @error('address')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="store-form-field">
                    <label for="phone">電話番号</label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                        autocomplete="tel" inputmode="tel" required>
                    @error('phone')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <button class="store-primary-button" type="submit">配送先情報を保存</button>
            </form>
        </details>

        <div class="mypage-actions">
            <a href="{{ route('orders.index') }}">注文履歴</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">ログアウト</button>
            </form>
        </div>

    </section>
@endsection
