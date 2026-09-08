@extends('layouts.base')

@section('title', $news->title ?? 'お知らせ')

@section('content')
    <div style="max-width: 760px; margin: 0 auto;">
        <!-- ヘッダーエリア（タイトルと一覧に戻るボタン） -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <span style="font-size: 0.9rem; color: #6b7280; font-weight: bold;">📢 お知らせ詳細</span>
            <a href="/" role="button" class="secondary outline" style="padding: 0.35rem 0.8rem; font-size: 0.85rem; width: auto; margin: 0;">
                ← トップへ戻る
            </a>
        </div>

        <article style="padding: 2rem; border-radius: 8px;">
            <header style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--pico-muted-border-color); padding-bottom: 1rem;">
                <h2 style="margin-bottom: 0.5rem; font-size: 1.6rem;">{{ $news->title }}</h2>
                @if (isset($news->created_at))
                    <small style="color: #6b7280;">公開日: {{ $news->created_at->format('Y年m月d日') }}</small>
                @endif
            </header>

            <div style="line-height: 1.8; font-size: 1.05rem; margin-bottom: 2rem; min-height: 100px;">
                {!! nl2br(e($news->content ?? $news->body ?? $news->detail ?? '')) !!}
            </div>

            <footer style="margin: 0; padding-top: 1rem; border-top: 1px solid var(--pico-muted-border-color); text-align: left;">
                <a href="/news" class="secondary outline" role="button" style="padding: 0.4rem 1rem; font-size: 0.9rem; width: auto; margin: 0;">
                    ← 一覧に戻る
                </a>
            </footer>
        </article>
    </div>
@endsection