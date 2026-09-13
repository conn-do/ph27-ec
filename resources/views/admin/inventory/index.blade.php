@extends('layouts.base')

@section('title', '在庫数設定')

@section('content')
<article style="max-width: 1000px; margin: 0 auto; padding: 2rem 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="margin: 0 0 0.5rem 0; font-size: 1.6rem;">📦 在庫数設定</h2>
            <p style="margin: 0; color: #64748b; font-size: 0.95rem;">各商品の在庫数（カラー別）を直接変更できます。</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="secondary outline" style="font-size: 0.85rem; padding: 0.4rem 0.8rem; text-decoration: none;">← ダッシュボードに戻る</a>
    </div>

    @if(session('message'))
        <div style="background: #dcfce7; color: #166534; padding: 0.8rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
            {{ session('message') }}
        </div>
    @endif

    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #475569;">
                    <th style="padding: 1rem; font-weight: 600; width: 35%;">商品名 / カラー</th>
                    <th style="padding: 1rem; font-weight: 600; width: 20%;">価格</th>
                    <th style="padding: 1rem; font-weight: 600; width: 45%;">在庫数変更</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr style="border-bottom: 1px solid #e2e8f0; vertical-align: top;">
                        <td style="padding: 1rem; color: #0f172a; font-weight: 500;">
                            {{ $product->name }}
                            
                            @if(!empty($product->colors) && is_array($product->colors))
                                <div style="margin-top: 0.5rem; font-size: 0.85rem; color: #64748b;">
                                    （カラーバリエーションあり）
                                </div>
                            @endif
                        </td>
                        <td style="padding: 1rem; color: #64748b;">
                            ¥{{ number_format($product->price) }}
                        </td>
                        <td style="padding: 1rem;">
                            @if(!empty($product->colors) && is_array($product->colors))
                                <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                                    @foreach($product->colors as $colorName => $colorData)
                                        <form action="{{ route('admin.products.updateStock', $product) }}" method="POST" style="display: flex; gap: 0.6rem; align-items: center; margin: 0;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="color_name" value="{{ $colorName }}">
                                            
                                            {{-- カラー名の幅を広げ（130px）、省略せずにすべて表示させる --}}
                                            <span style="font-size: 0.85rem; width: 130px; color: #334155; font-weight: 500;" title="{{ $colorName }}">
                                                {{ $colorName }}
                                            </span>
                                            <input type="number" name="stock" value="{{ $colorData['stock'] ?? 0 }}" min="0" style="width: 75px; padding: 0.3rem 0.5rem; font-size: 0.9rem; border: 1px solid #cbd5e1; border-radius: 4px;">
                                            <button type="submit" style="padding: 0.3rem 0.8rem; background: #2563eb; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.85rem;">更新</button>
                                        </form>
                                    @endforeach
                                </div>
                            @else
                                <form action="{{ route('admin.products.updateStock', $product) }}" method="POST" style="display: flex; gap: 0.5rem; align-items: center; margin: 0;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="stock" value="{{ $product->stock }}" min="0" style="width: 80px; padding: 0.3rem 0.5rem; font-size: 0.9rem; border: 1px solid #cbd5e1; border-radius: 6px;">
                                    <button type="submit" style="padding: 0.3rem 0.8rem; background: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.85rem; font-weight: 500;">更新</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</article>
@endsection