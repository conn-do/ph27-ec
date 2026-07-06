@extends('layouts.base')

@section('title', $news->title)

@section('content')
    <h5 class="news-tag">News</h5>
    <h2>{!! $news->title !!}</h2>
    <p>{!! $news->content !!}</p>
@endsection
