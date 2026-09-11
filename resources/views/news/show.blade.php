@extends('layouts.base')

@section('title', $news->title)

@section('content')

    <section class="news-detail-page">

        <div class="news-detail-heading">
            <span>NEWS</span>
            <h2>{{ $news->title }}</h2>
        </div>

        <div class="news-detail-content">
            <div class="news-detail-body">
                {!! $news->content !!}
            </div>
        </div>

        <div class="news-detail-back">
            <a href="/">
                <span>←</span>
                NEWS一覧に戻る
            </a>
        </div>

    </section>

@endsection