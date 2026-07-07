@extends('layouts.base')


@section('title', 'ニュース一覧')

@section('content')
    <h2>ニュース</h2>
    @foreach ($news as $topic)
        <ul>
            <li>
                {{ $topic['title'] }}
                {{ $topic['content'] }}
            </li>
        </ul>
    @endforeach
@endsection
