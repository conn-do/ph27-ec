@extends('layouts.base')

@section('title', '配送先住所の編集')

@section('content')

    <section class="address-edit-page">

        <div class="address-edit-heading">
            <h2>ADDRESS</h2>
            <p>配送先住所の編集</p>
        </div>

        <div class="address-edit-content">

            <form action="/address" method="POST" class="address-edit-form">
                @csrf

                <div class="address-edit-field">
                    <label for="postal_code">
                        郵便番号
                    </label>

                    <input
                        type="text"
                        id="postal_code"
                        name="postal_code"
                        value="{{ $address->postal_code }}"
                    >
                </div>

                <div class="address-edit-field">
                    <label for="address">
                        住所
                    </label>

                    <input
                        type="text"
                        id="address"
                        name="address"
                        value="{{ $address->address }}"
                    >
                </div>

                <input
                    type="submit"
                    value="保存する"
                    class="address-edit-submit"
                >
            </form>

        </div>

    </section>

@endsection