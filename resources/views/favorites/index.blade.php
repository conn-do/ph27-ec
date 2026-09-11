@extends('layouts.base')

@section('title', 'お気に入り一覧')

@section('content')
    <h1 class="text-2xl font-light my-14">お気に入り一覧</h1>

    @if (session('message'))
        <div class="border border-navy-200 rounded-2xl text-sm px-6 py-4 mb-8">{!! session('message') !!}</div>
    @endif

    @if (count($favorites) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-11 mb-20">
            @foreach ($favorites as $favorite)
                <div class="flex flex-col gap-4">
                    <a href="/products/{{ $favorite->product->id }}" class="block group">
                        <div class="bg-navy-100 rounded-2xl flex items-center justify-center h-64 overflow-hidden transition group-hover:shadow-xl">
                            <img src="{{ $favorite->product->imageUrl() }}" class="max-h-full max-w-full object-contain">
                        </div>
                    </a>
                    <div class="flex items-center justify-between gap-3">
                        <a href="/products/{{ $favorite->product->id }}" class="text-sm text-navy-950 hover:text-navy-500">
                            {{ $favorite->product->name }}
                        </a>
                        <a href="/favorites/remove/{{ $favorite->product->id }}" class="text-xs border border-navy-300 rounded-full px-3 py-1.5 text-navy-500 hover:bg-red-50 hover:text-red-600 hover:border-red-300 transition whitespace-nowrap">
                            解除
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-navy-500 mb-20">お気に入りに登録された商品はありません。</p>
    @endif
@endsection