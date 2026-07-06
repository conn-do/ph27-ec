@extends('layouts.base')

@section('title', $news->title)

@section('content')
    <h2 class="ochiru">{{$news->title }}</h2>
    <h2>{!!$news->content!!}</h2>
@endsection
