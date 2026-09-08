@extends('layouts.base')

@section('title', 'お知らせ一覧')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2>📢 お知らせ一覧</h2>
            <a href="/" role="button" class="secondary outline" style="padding: 0.35rem 0.8rem; font-size: 0.85rem; width: auto; margin: 0;">
                ← トップへ戻る
            </a>
        </div>

        @if (isset($newsList) && $newsList->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach ($newsList as $item)
                    <article style="padding: 1.2rem; margin: 0;">
                        <small style="color: #6b7280; display: block; margin-bottom: 0.3rem;">
                            {{ $item->created_at ? $item->created_at->format('Y年m月d日') : '' }}
                        </small>
                        <h3 style="font-size: 1.15rem; margin-bottom: 0.5rem;">
                            <a href="/news/{{ $item->id }}" style="text-decoration: none;">{{ $item->title }}</a>
                        </h3>
                        <p style="margin: 0; color: #4b5563; font-size: 0.95rem;">
                            {{ Str::limit($item->content ?? $item->body ?? '', 80) }}
                        </p>
                    </article>
                @endforeach
            </div>
        @elseif (isset($news) && $news->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach ($news as $item)
                    <article style="padding: 1.2rem; margin: 0;">
                        <small style="color: #6b7280; display: block; margin-bottom: 0.3rem;">
                            {{ $item->created_at ? $item->created_at->format('Y年m月d日') : '' }}
                        </small>
                        <h3 style="font-size: 1.15rem; margin-bottom: 0.5rem;">
                            <a href="/news/{{ $item->id }}" style="text-decoration: none;">{{ $item->title }}</a>
                        </h3>
                        <p style="margin: 0; color: #4b5563; font-size: 0.95rem;">
                            {{ Str::limit($item->content ?? $item->body ?? '', 80) }}
                        </p>
                    </article>
                @endforeach
            </div>
        @else
            <p>お知らせはありません。</p>
        @endif
    </div>
@endsection