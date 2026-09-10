@extends('layouts.base')

@section('title', $news->title)

@section('content')

    <div class="container">

        <div class="news-detail">

            <h1 class="news-detail-title">
                {{ $news->title }}
            </h1>

            <div class="news-detail-body">
                {!! $news->content !!}
            </div>

            <a href="/" class="back-link">
                ← トップページへ戻る
            </a>

        </div>

    </div>

@endsection
