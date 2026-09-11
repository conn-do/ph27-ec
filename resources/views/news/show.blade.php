@extends('layouts.base')

@section('title', $news->title)

@section('content')
    <div class="mx-auto max-w-2xl">
        <h1 class="text-xl font-bold text-stone-900">{{ $news->title }}</h1>

        <div class="mt-4 rounded-xl border border-stone-200 bg-white p-5 text-sm leading-relaxed text-stone-700">
            {!! $news->content !!}
        </div>

        <a href="/" class="mt-6 inline-block text-sm font-medium text-amber-700 hover:underline">← 戻る</a>
    </div>
@endsection
