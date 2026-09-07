@extends('layouts.base')

@section('title', $news->title)

@section('content')
<<<<<<< Updated upstream

    <div class="news-detail">

        <h1 class="news-detail-title">
            {{ $news->title }}
        </h1>

        <div class="news-detail-body">
            {!! $news->content !!}
        </div>

        <a href="/" class="back-link">← 戻る</a>

    </div>

@endsection
=======
    <article class="content-width news-detail">
        <a href="{{ route('home') }}" class="back-link">商品一覧に戻る</a>
        <p class="eyebrow">NEWS | {{ $news->created_at->format('Y.m.d') }}</p>
        <h1>{{ $news->title }}</h1>
        <div class="news-detail__body">
            {!! $news->content !!}
        </div>
    </article>
@endsection
>>>>>>> Stashed changes
