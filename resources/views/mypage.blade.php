@extends('layouts.base')

@section('title', 'わたしの文具棚')

@section('content')
    <section class="shelf-intro">
        <div>
            <p class="eyebrow">MY LITTLE COLLECTION</p>
            <h1>わたしの文具棚</h1>
            <p>{{ $user->name }}さん、こんにちは。<br>気になる道具を並べて、次のひらめきを待とう。</p>
        </div>
        <a class="button" href="{{ route('home') }}">新しい文具を探す ↗</a>
    </section>

    <section aria-labelledby="saved-heading">
        <div class="section-heading">
            <div><p class="eyebrow">SAVED FOR LATER</p><h2 id="saved-heading">気になる文具</h2></div>
            <span>{{ $favorites->count() }} items</span>
        </div>
        <div class="product-grid">
            @forelse ($favorites as $product)
                <div class="shelf-item">
                    <x-product-card :product="$product" />
                    <div class="shelf-item-actions">
                        <span>{{ $product->stock > 0 ? '在庫あり' : '入荷待ち' }}</span>
                        <form action="{{ route('favorites.destroy', $product) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="text-button" type="submit" aria-label="{{ $product->name }}を文具棚から外す">棚から外す</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <h3>まだ空っぽの、小さな文具棚。</h3>
                    <p>商品ページの「文具棚に保存」から、気になる文具を集められます。</p>
                    <a href="{{ route('home') }}">最初のひとつを探す →</a>
                </div>
            @endforelse
        </div>
    </section>

    <div class="member-panels">
        <section class="member-panel" aria-labelledby="profile-heading">
            <p class="eyebrow">ABOUT YOU</p>
            <h2 id="profile-heading">プロフィール</h2>
            <p>お名前とメールアドレスを変更できます。</p>
            <form action="{{ route('mypage.update') }}" method="POST" class="account-form">
                @csrf
                @method('PATCH')
                <div>
                    <label for="profile-name">お名前</label>
                    <input id="profile-name" name="name" value="{{ old('name', $user->name) }}" autocomplete="name" maxlength="255" required>
                </div>
                <div>
                    <label for="profile-email">メールアドレス</label>
                    <input id="profile-email" type="email" name="email" value="{{ old('email', $user->email) }}" autocomplete="email" maxlength="255" required>
                </div>
                <button type="submit">変更を保存する</button>
            </form>
        </section>
        <section class="member-panel" aria-labelledby="orders-heading">
            <p class="eyebrow">YOUR RECENT ORDERS</p>
            <h2 id="orders-heading">最近のお買いもの</h2>
            @forelse ($recentOrders as $order)
                <a class="member-order" href="/orders/{{ $order->id }}">
                    <span>注文 #{{ $order->id }}<small>{{ $order->created_at->format('Y.m.d') }}</small></span>
                    <strong>¥{{ number_format($order->total_price) }} ↗</strong>
                </a>
            @empty
                <p class="member-empty">まだご注文はありません。<br>お気に入りが見つかったら、商品ページからカートへ。</p>
            @endforelse
            <a class="back-link" href="/orders">すべての注文を見る →</a>
        </section>
    </div>
@endsection
