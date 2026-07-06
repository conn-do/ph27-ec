@extends('layouts.base')

@section('title', $news->title)

@section('content')
    <article class="news-detail">
        <a href="/" class="back-link">TOPへ戻る</a>
        <h2>{{ $news->title }}</h2>
        <time datetime="{{ $news->created_at->toDateString() }}">
            {{ $news->created_at->format('Y年m月d日') }}
        </time>
        <div class="news-content">
            {!! $news->content !!}
        </div>
    </article>
@endsection
