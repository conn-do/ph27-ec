@extends('layouts.base')

@section('title', $news->title)

@section('content')
    <div class="max-w-3xl mx-auto py-8">

        <!-- Navigation Breadcrumb -->
        <div class="mb-8">
            <a href="/"
                class="text-xs font-semibold uppercase tracking-widest text-neutral-400 hover:text-black transition">
                &larr; TOP
            </a>
        </div>

        <!-- Article Container -->
        <article class="bg-white border border-neutral-200 p-8 md:p-12 shadow-sm">

            <!-- Header -->
            <header class="border-b border-neutral-200 pb-6 mb-8">
                <div class="flex items-center justify-between text-xs text-neutral-400 font-mono mb-3">
                    <span class="uppercase tracking-widest text-neutral-500 font-sans font-semibold">NEWS</span>
                    @if (isset($news->created_at))
                        <span>{{ $news->created_at->format('Y.m.d') }}</span>
                    @endif
                </div>

                <h1 class="text-2xl md:text-3xl font-light tracking-tight text-neutral-900 leading-snug">
                    {{ $news->title }}
                </h1>
            </header>

            <!-- Body Content -->
            <div class="prose prose-neutral max-w-none text-xs md:text-sm text-neutral-700 leading-relaxed space-y-4 mb-12">
                {!! $news->content !!}
            </div>

            <!-- Back Link Button -->
            <div class="border-t border-neutral-100 pt-6">
                <a href="/"
                    class="inline-block bg-black text-white px-6 py-2.5 text-xs font-semibold uppercase tracking-widest hover:bg-neutral-800 transition">
                    &larr; 戻る
                </a>
            </div>

        </article>

    </div>
@endsection
