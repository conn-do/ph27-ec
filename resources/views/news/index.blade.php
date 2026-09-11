@extends('layouts.base')

@section('title', 'おしらせ')

@section('content')
    <x-page-heading emoji="📢" color="bg-pop-blue">
        おしらせ
    </x-page-heading>

    @if ($news->isEmpty())
        <x-empty-state emoji="📭">まだ おしらせが ないよ。</x-empty-state>
    @else
        <ul class="space-y-4">
            @foreach ($news as $topic)
                <li>
                    <a href="{{ route('news.show', $topic) }}"
                        class="flex gap-4 rounded-3xl border-4 border-ink bg-white p-5 shadow-block block-press">
                        <span class="text-3xl" aria-hidden="true">{{ $topic->emoji }}</span>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-ink/60">{{ $topic->published_at->format('Y年n月j日') }}</p>
                            <p class="text-lg font-black">{{ $topic->title }}</p>
                            <p class="mt-1 line-clamp-2 text-sm font-bold text-ink/70">{{ $topic->content }}</p>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="mt-8">
            {{ $news->links() }}
        </div>
    @endif
@endsection
