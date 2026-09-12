@extends('layouts.base')

@section('title', '商品一覧')

@section('content')
<div>
    <h2 style="font-size: 1.5rem; margin-bottom: 1.5rem; color: #1e293b;">
        📦 商品一覧
    </h2>

    @if (isset($products) && $products->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.5rem;">
            @foreach ($products as $product)
                <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; background: #fff; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="width: 100%; height: 160px; background: #f8fafc; border-radius: 6px; display: flex; align-items: center; justify-content: center; margin-bottom: 0.8rem; overflow: hidden;">
                            @if ($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" style="max-height: 100%; object-fit: contain;">
                            @else
                                <span style="color: #cbd5e1; font-size: 0.85rem;">No Image</span>
                            @endif
                        </div>
                        <h3 style="font-size: 1rem; font-weight: bold; margin-bottom: 0.4rem; line-height: 1.3;">
                            {{ $product->name }}
                        </h3>
                        <p style="font-size: 0.8rem; color: #64748b; margin-bottom: 0.8rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $product->description }}
                        </p>
                    </div>

                    <div>
                        <div style="margin-bottom: 0.8rem;">
                            @if ($product->is_sale && $product->sale_price)
                                <span style="text-decoration: line-through; color: #9ca3af; font-size: 0.85rem; margin-right: 0.4rem;">
                                    ¥{{ number_format($product->price) }}
                                </span>
                                <strong style="color: #ef4444; font-size: 1.1rem;">
                                    ¥{{ number_format($product->sale_price) }}
                                </strong>
                            @else
                                <strong style="font-size: 1.1rem; color: #1e293b;">
                                    ¥{{ number_format($product->price) }}
                                </strong>
                            @endif
                        </div>

                        <a href="{{ route('products.show', $product->id) }}" role="button" style="width: 100%; text-align: center; padding: 0.4rem 0.8rem; font-size: 0.88rem;">
                            詳細を見る
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p style="color: #64748b;">該当する商品が見つかりませんでした。</p>
    @endif
</div>
@endsection