@extends('layouts.base')

@section('title', '管理者ダッシュボード')

@section('content')
<article style="max-width: 900px; margin: 0 auto; padding: 2rem 1.5rem;">
    <div style="margin-bottom: 2rem;">
        <h2 style="margin: 0 0 0.5rem 0; font-size: 1.6rem;">⚙️ 管理者ダッシュボード</h2>
        <p style="margin: 0; color: #64748b; font-size: 0.95rem;">管理するメニューを選択してください。</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
        {{-- 注文・配送管理カード --}}
        <a href="/admin/orders" style="text-decoration: none; color: inherit; display: block;">
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.8rem; height: 100%; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.05);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px -3px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.05)';">
                <div style="font-size: 2.2rem; margin-bottom: 1rem;">📦</div>
                <h3 style="margin: 0 0 0.5rem 0; font-size: 1.25rem; color: #0f172a;">注文・配送状況管理</h3>
                <p style="margin: 0; font-size: 0.875rem; color: #64748b; line-height: 1.5;">
                    お客様からの注文一覧の確認や、発送ステータス（準備中・発送済み等）の更新を行います。
                </p>
                <div style="margin-top: 1.5rem; font-weight: bold; font-size: 0.9rem; color: #2563eb; display: flex; align-items: center; gap: 0.3rem;">
                    管理画面を開く →
                </div>
            </div>
        </a>

        {{-- 商品・セール価格管理カード --}}
        <a href="/admin/sales" style="text-decoration: none; color: inherit; display: block;">
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.8rem; height: 100%; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.05);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px -3px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.05)';">
                <div style="font-size: 2.2rem; margin-bottom: 1rem;">🏷️</div>
                <h3 style="margin: 0 0 0.5rem 0; font-size: 1.25rem; color: #0f172a;">商品・セール価格管理</h3>
                <p style="margin: 0; font-size: 0.875rem; color: #64748b; line-height: 1.5;">
                    商品の通常価格、割引率設定、セール中フラグのON/OFF切替を行います。
                </p>
                <div style="margin-top: 1.5rem; font-weight: bold; font-size: 0.9rem; color: #2563eb; display: flex; align-items: center; gap: 0.3rem;">
                    管理画面を開く →
                </div>
            </div>
        </a>
    </div>

    <div style="margin-top: 2rem;">
        <a href="/mypage" class="secondary outline" style="font-size: 0.85rem; padding: 0.4rem 0.8rem; width: auto; text-decoration: none;">← マイページに戻る</a>
    </div>
</article>
@endsection