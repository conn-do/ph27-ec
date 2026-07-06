@extends('layouts.base')

@section('title', '商品一覧')

@section('content')
    <div style="background: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin-bottom: 30px; border-radius: 5px;">
        <h3 style="margin-top: 0; border-bottom: 2px solid #3490dc; padding-bottom: 5px;">最新のお知らせ</h3>
        @if($news->isEmpty())
            <p style="color: #666; font-size: 14px;">現在お知らせはありません。</p>
        @else
            <ul style="list-style: none; padding-left: 0; margin-bottom: 0;">
                @foreach ($news as $item)
                    <li style="margin-bottom: 10px; border-bottom: 1px dashed #eee; padding-bottom: 8px;">
                        <span style="color: #888; font-size: 13px; margin-right: 15px;">
                            {{ $item->created_at->format('Y/m/d') }}
                        </span>
                        <!-- お知らせ詳細画面へ -->
                        <a href="{{ route('news.show', $item) }}" style="color: #3490dc; text-decoration: none; font-weight: bold;">
                            {{ $item->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- ========================================== -->
    <!-- 商品一覧コーナー                          -->
    <!-- ========================================== -->
    <h2>商品一覧</h2>
    <form action="/search" method="GET">
        <input type="text" name="keyword" value="{{ request('keyword') }}">
        <input type="submit" value="検索">
    </form>
    
    @if (request('keyword'))
        <a href="/">検索結果をクリア</a>
    @endif

    <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px;">
        @foreach ($products as $product)
            <ul style="list-style: none; padding: 0;">
                <li>
                    <a href="/products/{{ $product->id }}" style="text-decoration: none; color: #333;">
                        <div style="font-weight: bold; margin-bottom: 5px;">{{ $product['name'] }}</div>
                        <img src="{{ $product->imageUrl() }}" width="200" style="border: 1px solid #ddd; border-radius: 4px;">
                    </a>
                </li>
            </ul>
        @endforeach
    </div>
@endsection