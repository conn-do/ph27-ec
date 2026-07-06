@extends('layouts.base')

@section('title', $news->title)

@section('content')

<div class="news">

    <h1>{{ $news->title }}</h1>

    <div>
        {!! $news->content !!}
    </div>

</div>

@endsection