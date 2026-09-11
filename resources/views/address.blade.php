@extends('layouts.base')

@section('title', '配送先住所')

@section('content')

    <section class="address-page">

        <div class="address-heading">
            <h2>ADDRESS</h2>
            <p>配送先住所</p>
        </div>

        @if (session('message'))
            <div class="address-message">
                <p>{{ session('message') }}</p>
            </div>
        @endif

        @if ($address)

            <div class="address-content">

                <div class="address-info">

                    <div class="address-row">
                        <span>郵便番号</span>
                        <strong>{{ $address->postal_code }}</strong>
                    </div>

                    <div class="address-row">
                        <span>住所</span>
                        <strong>{{ $address->address }}</strong>
                    </div>

                </div>

                <a href="/address/edit" class="address-edit-button">
                    <span>編集する</span>
                    <span class="address-edit-arrow">→</span>
                </a>

            </div>

        @else

            <div class="address-content">

                <p class="address-empty">
                    配送先住所は登録されていません。
                </p>

                <form action="/address" method="POST" class="address-form">
                    @csrf

                    <div class="address-field">
                        <label for="postal_code">
                            郵便番号
                        </label>

                        <input
                            type="text"
                            id="postal_code"
                            name="postal_code"
                        >
                    </div>

                    <div class="address-field">
                        <label for="address">
                            住所
                        </label>

                        <input
                            type="text"
                            id="address"
                            name="address"
                        >
                    </div>

                    <input
                        type="submit"
                        value="保存する"
                        class="address-submit"
                    >
                </form>

            </div>

        @endif

    </section>

@endsection