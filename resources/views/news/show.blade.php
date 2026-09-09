@extends('layouts.base')

@section('title', $news->title)

@section('content')
    <article class="news-detail">
        <h1>{{ $news->title }}</h1>
        <div class="news-detail__body">{!! $news->content !!}</div>
        <a href="{{ route('home') }}">商品一覧に戻る</a>
    </article>
@endsection
