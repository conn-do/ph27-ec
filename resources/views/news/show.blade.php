@extends('layouts.base')
@section('title', $news->title)
@section('content')
    <article class="news-detail">
        <p class="eyebrow">
            FROM YOHAKU / {{ $news->created_at->format('Y.m.d') }}
        </p>
        <h1>
            {{ $news->title }}
        </h1>
        <div class="description">
            {{ strip_tags($news->content) }}
        </div>
        <a href="{{ route('home') }}#journal">
            ← お知らせ一覧へ
        </a>
    </article>
@endsection
