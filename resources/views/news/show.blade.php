@extends('layouts.base')
@section('title', $news->title)
@section('content')
    <article class="mx-auto max-w-3xl px-5 py-14 lg:px-8"><a href="{{ route('home') }}" class="font-bold text-slate-500 hover:text-amber-700">← トップへ戻る</a><header class="mt-8 border-b border-stone-200 pb-8"><p class="font-black text-amber-600">NEWS</p><h1 class="mt-2 text-4xl font-black tracking-tight sm:text-5xl">{{ $news->title }}</h1><time class="mt-4 block text-sm text-slate-500">{{ $news->created_at->format('Y年m月d日') }}</time></header><div class="mt-8 whitespace-pre-line text-lg leading-9 text-slate-700">{{ $news->content }}</div></article>
@endsection
