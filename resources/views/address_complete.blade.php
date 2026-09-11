@extends('layouts.base')

@section('title', '保存完了')

@section('content')

    <section class="address-complete-page">

        <div class="address-complete-heading">
            <h2>ADDRESS</h2>
            <p>配送先住所</p>
        </div>

        <div class="address-complete-content">

            <p class="address-complete-message">
                住所を保存しました。
            </p>

            <a href="/address" class="address-complete-button">
                住所を確認する
            </a>

        </div>

    </section>

@endsection