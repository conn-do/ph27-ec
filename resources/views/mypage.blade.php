@extends('layouts.base')
@section('title', 'マイページ')
@section('content')
    <div class="page-heading">
        <p class="eyebrow">
            MY ACCOUNT
        </p>
        <h1>
            {{ auth()->user()->name }}さん、こんにちは。
        </h1>
        <p>
            今日も、お気に入りの道具とともに。
        </p>
    </div>
    <div class="account-layout">
        <section>
            <h2>
                アカウント情報
            </h2>
            <p class="address">
                {{ auth()->user()->email }}
            </p>
            <a href="{{ route('profile.edit') }}">
                プロフィールを編集する ↗
            </a>
            <form class="logout-form" action="{{ route('logout') }}" method="post" data-submit>
                @csrf
                <button class="text-button" type="submit">
                    ログアウト
                </button>
            </form>
        </section>
        <a class="account-order-link" href="{{ route('orders.index') }}">
            <p class="eyebrow">
                YOUR ORDERS
            </p>
            <h2>
                注文履歴
            </h2>
            <p>
                ご注文の商品・お届け先を確認する ↗
            </p>
        </a>
    </div>
@endsection
