@extends('layouts.base')

@section('title', $category->name)

@section('content')

<div>
    {{-- 戻るリンク --}}
    <div style="margin-bottom: 20px;">
        <a href="/" style="color: #38bdf8; text-decoration: none; font-size: 13px;">← トップページへ戻る</a>
    </div>

    {{-- カテゴリ名（見出し） --}}
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 16px; font-weight: 600; color: #e5e7eb; margin: 0; border-left: 3px solid #38bdf8; padding-left: 10px;">
            {{ $category->name }}
        </h2>
    </div>

    {{-- 商品一覧グリッド --}}
    <div style="margin-bottom: 40px;">
        @if($category->products && $category->products->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                @foreach ($category->products as $product)
                    @php
                        $imageFile = 'note.png';
                        $pName = $product->name ?? '';
                        if (str_contains($pName, 'ペン') || $product->id == 1) {
                            $imageFile = 'pen.png';
                        } elseif (str_contains($pName, 'ノート') || $product->id == 2) {
                            $imageFile = 'note.png';
                        } elseif (str_contains($pName, '鉛筆') || $product->id == 3) {
                            $imageFile = 'pencil.png';
                        } elseif (method_exists($product, 'imageUrl') && !empty($product->imageUrl())) {
                            $imageFile = basename($product->imageUrl());
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
        @else
            <p style="color: #9ca3af; font-size: 14px;">このカテゴリーに属する商品はまだありません。</p>
        @endif
    </div>
</div>

@endsection