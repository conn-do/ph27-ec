@extends('layouts.base')

@section('title', 'マイページ')

@section('content')
    <a href="/orders">注文履歴</a>
@endsection
a@extends('layouts.base')

@section('title', 'マイページ')

@section('content')

    <div class="mypage-page">

        {{-- =========================
         Header
    ========================= --}}

        <section class="mypage-header">

            <p class="mypage-label">
                PH27 STATIONERY / MEMBERS
            </p>

            <h1 class="mypage-title">
                MY PAGE
            </h1>

            <p class="mypage-subtitle">
                アカウント情報とご注文を管理できます。
            </p>

        </section>


        {{-- =========================
         Message
    ========================= --}}

        @if (session('message'))
            <div class="mypage-message">
                {{ session('message') }}
            </div>
        @endif


        {{-- =========================
         Profile
    ========================= --}}

        <section class="mypage-section">

            <div class="section-heading">

                <h2>
                    プロフィール
                </h2>

                <span class="section-number">
                    01 / PROFILE
                </span>

            </div>


            <div class="profile-info">

                <div class="profile-row">

                    <span class="profile-label">
                        NAME
                    </span>

                    <span class="profile-value">
                        {{ $user->name }}
                    </span>

                </div>


                <div class="profile-row">

                    <span class="profile-label">
                        EMAIL
                    </span>

                    <span class="profile-value">
                        {{ $user->email }}
                    </span>

                </div>

            </div>


            <a href="/mypage/edit" class="mypage-outline-button">
                PROFILE EDIT
            </a>

        </section>


        {{-- =========================
         Orders
    ========================= --}}

        <section class="mypage-section">

            <div class="section-heading">

                <h2>
                    注文履歴
                </h2>

                <span class="section-number">
                    02 / ORDERS
                </span>

            </div>


            @if ($orders->count() > 0)

                <div class="mypage-order-list">

                    @foreach ($orders->take(5) as $order)
                        <a href="/orders/{{ $order->id }}" class="mypage-order-item">

                            <div class="mypage-order-main">

                                <span class="mypage-order-number">
                                    ORDER #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                </span>

                                <span class="mypage-order-date">
                                    {{ $order->created_at->format('Y.m.d') }}
                                </span>

                            </div>


                            <div class="mypage-order-side">

                                <span class="mypage-order-price">
                                    ¥{{ number_format($order->total_price) }}
                                </span>

                                <span class="mypage-order-arrow">
                                    →
                                </span>

                            </div>

                        </a>
                    @endforeach

                </div>


                <div class="mypage-more">

                    <a href="/orders" class="mypage-more-link">
                        VIEW ALL ORDERS →
                    </a>

                </div>
            @else
                <div class="mypage-empty">

                    <p>
                        まだ注文履歴がありません。
                    </p>

                    <a href="/" class="mypage-more-link">
                        BACK TO CATALOG →
                    </a>

                </div>

            @endif

        </section>


        {{-- =========================
         Account
    ========================= --}}

        <section class="mypage-section">

            <div class="section-heading">

                <h2>
                    アカウント
                </h2>

                <span class="section-number">
                    03 / ACCOUNT
                </span>

            </div>


            <div class="mypage-account-links">

                <a href="/cart" class="mypage-account-link">
                    <span>
                        カートを見る
                    </span>

                    <span>
                        →
                    </span>
                </a>


                <form method="POST" action="{{ route('logout') }}" class="mypage-logout-form">

                    @csrf

                    <button type="submit" class="mypage-account-link mypage-logout-button">
                        <span>
                            ログアウト
                        </span>

                        <span>
                            →
                        </span>
                    </button>

                </form>

            </div>

        </section>

    </div>

@endsection
