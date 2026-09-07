@extends('layouts.base')
@section('title', 'マイページ')
@section('content')
    <div class="page-heading">
        <p class="eyebrow">MY ACCOUNT</p>
        <h1>{{ auth()->user()->name }}さん、こんにちは。</h1>
        <p>お気に入りと注文、プロフィールをひとつの場所で管理できます。</p>
    </div>

    <div class="account-summary" aria-label="アカウントの概要">
        <a href="{{ route('favorites.index') }}">
            <span>お気に入り</span>
            <strong>{{ $favoritesCount }}</strong>
            <small>保存した商品を見る ↗</small>
        </a>
        <a href="{{ route('orders.index') }}">
            <span>注文履歴</span>
            <strong>{{ $ordersCount }}</strong>
            <small>これまでの注文を見る ↗</small>
        </a>
    </div>

    <section class="profile-section" aria-labelledby="profile-title">
        <div class="section-heading">
            <div>
                <p class="eyebrow">PROFILE</p>
                <h2 id="profile-title">プロフィール情報</h2>
            </div>
            <span>名前とメールアドレスを変更できます</span>
        </div>
        <form class="profile-form" action="{{ route('profile.update') }}" method="post" data-submit>
            @csrf
            @method('patch')
            <div class="form-field">
                <label for="profile-name">お名前 <span>必須</span></label>
                <input id="profile-name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" maxlength="255" autocomplete="name" required @error('name') aria-invalid="true" aria-describedby="profile-name-error" @enderror>
                @error('name')
                    <p class="field-error" id="profile-name-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-field">
                <label for="profile-email">メールアドレス <span>必須</span></label>
                <input id="profile-email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" maxlength="255" autocomplete="email" required @error('email') aria-invalid="true" aria-describedby="profile-email-error" @enderror>
                @error('email')
                    <p class="field-error" id="profile-email-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="profile-actions">
                <button class="button" type="submit">変更を保存する</button>
                <a class="text-button" href="{{ route('security.edit') }}">パスワード・セキュリティ設定 ↗</a>
            </div>
        </form>
    </section>

    <section class="favorite-preview" aria-labelledby="favorite-preview-title">
        <div class="section-heading">
            <div>
                <p class="eyebrow">SAVED ITEMS</p>
                <h2 id="favorite-preview-title">最近のお気に入り</h2>
            </div>
            @if ($favorites->isNotEmpty())
                <a href="{{ route('favorites.index') }}">すべて見る ↗</a>
            @endif
        </div>
        <div class="product-grid account-product-grid">
            @forelse ($favorites as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="empty-state compact-empty-state">
                    <h3>気になる道具を保存してみませんか。</h3>
                    <p>商品のハートを押すと、マイページからすぐに見返せます。</p>
                    <a class="button button-outline" href="{{ route('home') }}#collection">商品を探す</a>
                </div>
            @endforelse
        </div>
    </section>

    <form class="logout-form account-logout" action="{{ route('logout') }}" method="post" data-submit>
        @csrf
        <button class="text-button" type="submit">ログアウト</button>
    </form>
@endsection
