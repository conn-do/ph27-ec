@extends('layouts.base')

@section('title', 'お問い合わせ')

@section('content')
    <div class="max-w-lg">
        <h1 class="mb-6 text-xl font-bold text-stone-900">お問い合わせ</h1>

        @if ($errors->any())
            <div class="mb-4 space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="/contact" method="post" class="space-y-4 rounded-xl border border-stone-200 bg-white p-6">
            @csrf

            <label class="block">
                <span class="mb-1 block text-sm font-medium text-stone-700">お名前</span>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
            </label>

            <label class="block">
                <span class="mb-1 block text-sm font-medium text-stone-700">メールアドレス</span>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
            </label>

            <label class="block">
                <span class="mb-1 block text-sm font-medium text-stone-700">お問い合わせ内容</span>
                <textarea name="message" rows="6"
                    class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">{{ old('message') }}</textarea>
            </label>

            <button type="submit"
                class="w-full rounded-lg bg-amber-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-amber-700">
                送信する
            </button>
        </form>
    </div>
@endsection
