@extends('layouts.base')

@section('title', '商品一覧')

@section('content')

<div>
    {{-- 検索セクション --}}
    <div style="margin-bottom: 35px;">
        <form action="/search" method="GET" style="display: flex; width: 100%; background-color: #181d2b; border: 1px solid #2a3245; overflow: hidden; border-radius: 4px;">
            <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="キーワードを入力..." style="flex: 1; padding: 12px 16px; background: transparent; border: none; color: #ffffff; font-size: 14px; outline: none; box-shadow: none;">
            <button type="submit" style="padding: 0 24px; background-color: #1f293d; color: #38bdf8; border: none; border-left: 1px solid #2a3245; font-weight: 600; font-size: 14px; cursor: pointer; white-space: nowrap; margin: 0; border-radius: 0;">検索</button>
        </form>

        @if (request('keyword'))
            <div style="margin-top: 8px;">
                <a href="/" style="color: #6b7280; text-decoration: none; font-size: 13px;">✕ 検索結果をクリア</a>
            </div>
        @endif
    </div>

    {{-- カテゴリセクション --}}
    <div style="margin-bottom: 40px;">
        <h3 style="font-size: 12px; font-weight: 600; letter-spacing: 0.1em; color: #9ca3af; margin-bottom: 12px;">カテゴリー</h3>
        <ul style="display: flex; flex-wrap: wrap; gap: 8px; list-style: none; padding: 0; margin: 0;">
            @foreach ($categories as $category)
                <li style="margin: 0; padding: 0; list-style: none;">
                    <a href="/categories/{{ $category->slug }}" style="color: #d1d5db; text-decoration: none; display: inline-block; padding: 8px 16px; background: #181d2b; border: 1px solid #2a3245; font-size: 13px; border-radius: 4px;">
                        {{ $category->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- 商品一覧セクション --}}
    <div style="margin-bottom: 40px;">
        <h2 style="font-size: 12px; font-weight: 600; margin-bottom: 16px; color: #9ca3af; letter-spacing: 0.1em;">商品一覧</h2>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
            @foreach ($products as $product)
                @php
                    $imageFile = 'note.png';
                    $pName = $product->name ?? '';
                    if (str_contains($pName, 'ペン') || $product->id == 1) {
                        $imageFile = 'pen.png';
                    } elseif (str_contains($pName, 'ノート') || $product->id == 2) {
                        $imageFile = 'note.png';
                    } elseif (str_contains($pName, '鉛筆') || $product->id == 3) {
                        $imageFile = 'pencil.png';
                    } elseif (!empty($product->image)) {
                        $imageFile = basename($product->image);
                    }
                @endphp

                <div style="background: #13161E; border: 1px solid #2a3245; padding: 12px; display: flex; flex-direction: column; justify-content: space-between; border-radius: 4px;">
                    <a href="/products/{{ $product->id }}" style="text-decoration: none; color: inherit;">
                        <div style="width: 100%; height: 160px; background: #0e1117; overflow: hidden; margin-bottom: 10px; display: flex; align-items: center; justify-content: center; border: 1px solid #1a2233;">
                            <img src="{{ asset('images/products/' . $imageFile) }}" alt="{{ $pName }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h3 style="font-size: 13px; font-weight: 400; margin-bottom: 6px; color: #e5e7eb; margin-top: 0;">{{ $pName }}</h3>
                    </a>
                    <p style="color: #38bdf8; font-weight: 600; font-size: 14px; margin: 0;">¥{{ number_format($product->price ?? 0) }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- お知らせセクション --}}
    <div>
        <h2 style="font-size: 12px; font-weight: 600; margin-bottom: 16px; color: #9ca3af; letter-spacing: 0.1em;">お知らせ</h2>

        <div style="display: flex; flex-direction: column; border-top: 1px solid #2a3245;">
            @foreach ($news as $item)
                <div style="padding: 12px 0; border-bottom: 1px solid #2a3245;">
                    <h4 style="margin: 0 0 2px 0;">
                        <a href="/news/{{ $item->id }}" style="color: #e5e7eb; text-decoration: none; font-size: 13px; font-weight: 500;">
                            {{ $item->title }}
                        </a>
                    </h4>
                    <p style="font-size: 12px; color: #9ca3af; margin: 0;">
                        {!! $item->content !!}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection