@extends('layouts.base')

@section('title', 'マイページ')

@section('content')
    <div class="content-width narrow-page">
        <div class="page-heading">
            <div>
                <p class="eyebrow">MY PAGE</p>
                <h1>マイページ</h1>
            </div>
        </div>
        <section class="account-links">
            <a href="{{ route('orders.index') }}">注文履歴を見る</a>
            <a href="{{ route('profile.edit') }}">プロフィールを編集する</a>
            <a href="{{ route('home') }}">商品を探す</a>
        </section>
    </div>
@endsection
