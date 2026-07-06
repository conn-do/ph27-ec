@extends('layouts.base')

@section('title', $news->title)

@section('content')
    <article class="news-detail">
        <h2>{{ $news->title }}</h2>
        <div class="news-content">
            {!! $news->content !!}
        </div>
    </article>
@endsection
