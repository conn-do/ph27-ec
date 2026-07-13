@extends('layouts.base')

@section('title', $news->title)

@section('content')
    <article class="news-detail">
        <h1 class="news-detail-title">
            {{ $news->title }}
        </h1>

        <div class="news-detail-body">
            {!! $news->content !!}
        </div>

        <a href="{{ route('home') }}" class="back-link">← 戻る</a>
    </article>
@endsection
