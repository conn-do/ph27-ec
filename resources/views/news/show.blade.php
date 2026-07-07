@extends('layouts.base')

@section('title', 'ニュース詳細')

@section('content')

    <h1>{{ $news->title }}</h1>

    <div>
        {!! $news->content !!}
    </div>

@endsection
