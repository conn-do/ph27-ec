@extends('layouts.base')

@section('title', $news->title)

@section('content')
    <h2>{{ $news->title }}</h2>
    <div>
        {!! $news->content !!}
    </div>
@endsection
