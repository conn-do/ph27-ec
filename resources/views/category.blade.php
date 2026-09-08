@extends('layouts.base')

@section('title', $category->name . 'の商品一覧')

@section('content')
<div style="background-color: #13161E; color: #fff; max-width: 1200px; margin: 0 auto; padding: 20px;">

    {{-- パンくず・戻るリンク --}}
    <div style="margin-bottom: 24px;">
        <a href="/" style="color: #888; font-size: 12px; text-decoration: none;">← ホームに戻る</a>
    </div>

    {{-- カテゴリタイトル --}}
    <div style="margin-bottom: 40px;">
        <h2 style="font-size: 24px; font-weight: 700; color: #fff; margin: 0;">
            「{{ $category->name }}」のカテゴリ商品一覧
        </h2>
    </div>

    {{-- 商品一覧 --}}
    <div style="margin-bottom: 60px;">
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
                    このカテゴリに登録されている商品はまだありません。
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection