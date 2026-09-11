@extends('layouts.base')

@section('title', $topic->title)

@section('content')
    <a href="{{ route('news.index') }}"
        class="mb-4 inline-block text-sm font-black underline decoration-4 decoration-pop-blue underline-offset-4">
        ← おしらせ いちらん
    </a>

    <article class="rounded-3xl border-4 border-ink bg-white p-6 shadow-block sm:p-8">
        <p class="text-5xl" aria-hidden="true">{{ $topic->emoji }}</p>
        <p class="mt-3 text-sm font-bold text-ink/60">{{ $topic->published_at->format('Y年n月j日') }}</p>
        <h1 class="mt-1 text-2xl font-black sm:text-3xl">{{ $topic->title }}</h1>
        <p class="mt-5 text-base leading-loose font-bold">{{ $topic->content }}</p>
    </article>
@endsection
