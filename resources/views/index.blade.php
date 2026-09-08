@extends('layouts.base')

@section('title', '商品一覧')

@section('content')
<div style="background-color: #13161E; color: #fff; max-width: 1200px; margin: 0 auto; padding: 20px;">

    {{-- カテゴリ一覧 --}}
    <div style="margin-bottom: 40px;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: #fff;">カテゴリ</h3>
        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap; gap: 12px;">
            @foreach ($categories as $category)
                <li>
                    <a href="/categories/{{ $category->slug }}" style="display: inline-block; padding: 6px 12px; background-color: #1a1e29; color: #fff; text-decoration: none; font-size: 12px; border: 1px solid #2a2e3d;">
                        {{ $category->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- 検索フォーム --}}
    <div style="margin-bottom: 40px;">
        <form action="/search" method="GET" style="display: flex; gap: 8px; max-width: 400px;">
            <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="キーワードで検索" style="flex: 1; padding: 8px; background-color: #1a1e29; border: 1px solid #2a2e3d; color: #fff; outline: none;">
            <input type="submit" value="検索" style="padding: 8px 16px; background-color: #fff; color: #13161E; border: none; font-weight: 700; cursor: pointer;">
        </form>

        @if (request('keyword'))
            <div style="margin-top: 8px;">
                <a href="/" style="color: #888; font-size: 12px; text-decoration: underline;">検索結果をクリア</a>
            </div>
        @endif
    </div>

    {{-- 商品一覧 --}}
    <div style="margin-bottom: 60px;">
        <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 24px; color: #fff;">商品一覧</h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 24px; width: 100%;">
            @forelse ($products as $product)
                <div style="background-color: #13161E; display: flex; flex-direction: column;">
                    <a href="/products/{{ $product->id }}" style="text-decoration: none; display: block; margin-bottom: 10px;">
                        <div style="width: 100%; height: 160px; background-color: #1a1e29; overflow: hidden; margin-bottom: 8px;">
                            <img src="{{ $product->imageUrl() }}" alt="{{ $product['name'] }}" style="width: 100%; height: 100%; object-fit: cover; filter: grayscale(100%); display: block;">
                        </div>
                        <h3 style="font-size: 14px; font-weight: 700; color: #fff; margin: 0 0 6px 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $product['name'] }}</h3>
                    </a>
                
                    <div style="font-size: 13px; font-weight: 700; color: #fff;">
                        ¥{{ number_format($product->price) }} <span style="font-size: 10px; color: #888; font-weight: normal;">（税込）</span>
                    </div>
                </div>
            @empty
                <div style="color: #666; font-size: 14px; grid-column: 1 / -1;">
                    商品はまだ登録されていません。
                </div>
            @endforelse
        </div>
    </div>

    {{-- お知らせ --}}
    <div style="border-top: 1px solid #2a2e3d; padding-top: 40px;">
        <h2 style="font-size: 16px; font-weight: 700; color: #888; margin-bottom: 4px; letter-spacing: 0.05em;">NEWS</h2>
        <h3 style="font-size: 20px; font-weight: 700; color: #fff; margin-bottom: 24px;">お知らせ</h3>

        <div style="display: flex; flex-direction: column; gap: 20px;">
            @foreach ($news as $item)
                <div style="padding-bottom: 16px; border-bottom: 1px solid #1a1e29;">
                    <h4 style="font-size: 14px; font-weight: 700; margin-bottom: 6px;">
                        <a href="/news/{{ $item->id }}" style="color: #fff; text-decoration: none;">
                            {{ $item->title }}
                        </a>
                    </h4>

                    <div style="font-size: 13px; color: #aaa; line-height: 1.6;">
                        {!! $item->content !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection