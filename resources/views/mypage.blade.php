@extends('layouts.base')

@section('title', 'マイページ')

@section('content')
    <h1 class="mb-6 text-xl font-bold text-stone-900">マイページ</h1>

    <div class="flex flex-col gap-3 sm:flex-row">
        <a href="/orders"
            class="inline-flex items-center gap-2 rounded-xl border border-stone-200 bg-white px-5 py-4 text-sm font-medium text-stone-800 shadow-sm transition hover:shadow-md">
            注文履歴
            <svg class="h-4 w-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>

        <a href="/favorites"
            class="inline-flex items-center gap-2 rounded-xl border border-stone-200 bg-white px-5 py-4 text-sm font-medium text-stone-800 shadow-sm transition hover:shadow-md">
            お気に入り
            <svg class="h-4 w-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>
@endsection
