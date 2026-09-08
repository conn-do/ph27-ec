@extends('layouts.base')

@section('title', $news->title)

@section('content')

<div style="max-width: 900px; margin: 0 auto; color: #e5e7eb;">

    {{-- 戻るリンク --}}
    <div style="margin-bottom: 20px;">
        <a href="/" style="color: #38bdf8; text-decoration: none; font-size: 13px;">← トップページへ戻る</a>
    </div>

    {{-- ニュース詳細カード --}}
    <div style="background: #13161E; border: 1px solid #2a3245; padding: 28px; border-radius: 4px;">
        
        {{-- メタ情報（タグ・日付など） --}}
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
            <span style="background-color: #1f293d; color: #38bdf8; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 3px; border: 1px solid #2a3245;">
                お知らせ
            </span>
            <span style="font-size: 12px; color: #9ca3af;">
                投稿日: 2026.09.11
            </span>
            <span style="font-size: 12px; color: #9ca3af; margin-left: auto;">
                運営元: すごい文房具EC運営チーム
            </span>
        </div>

        {{-- タイトル --}}
        <h1 style="font-size: 18px; font-weight: 600; color: #e5e7eb; margin-top: 0; margin-bottom: 20px; border-left: 3px solid #38bdf8; padding-left: 10px;">
            {{ $news->title }}
        </h1>

        {{-- 本文 --}}
        <div style="font-size: 14px; color: #d1d5db; line-height: 1.8; border-top: 1px solid #2a3245; padding-top: 20px; margin-bottom: 30px;">
            {!! $news->content !!}
        </div>

        {{-- 下部のアクションエリア・付加情報 --}}
        <div style="border-top: 1px solid #2a3245; padding-top: 20px; display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #9ca3af;">
            <div>
                <span>この記事の共有: </span>
                <a href="#" style="color: #38bdf8; text-decoration: none; margin-left: 8px;">X (Twitter)</a>
                <a href="#" style="color: #38bdf8; text-decoration: none; margin-left: 8px;">Line</a>
            </div>
            <div>
                <a href="/" style="color: #38bdf8; text-decoration: none;">トップページへ戻る →</a>
            </div>
        </div>

    </div>

</div>

@endsection