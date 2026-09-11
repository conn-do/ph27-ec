@extends('layouts.base')

@section('title', 'マイページ')

@section('content')

    <section class="mypage">

        <div class="mypage-heading">
            <h2>MY PAGE</h2>
            <p>マイページ</p>
        </div>

        <div class="mypage-menu">

            <a href="/orders" class="mypage-menu-item">
                <div>
                    <span class="mypage-menu-en">ORDER HISTORY</span>
                    <h3>注文履歴</h3>
                </div>

                <span class="mypage-menu-arrow">→</span>
            </a>

            <a href="/address" class="mypage-menu-item">
                <div>
                    <span class="mypage-menu-en">ADDRESS</span>
                    <h3>配送先住所</h3>
                </div>

                <span class="mypage-menu-arrow">→</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="mypage-menu-item mypage-logout">
                @csrf

                <button type="submit">
                    <div>
                        <span class="mypage-menu-en">LOGOUT</span>
                        <h3>ログアウト</h3>
                    </div>

                    <span class="mypage-menu-arrow">→</span>
                </button>
            </form>

        </div>

    </section>

@endsection