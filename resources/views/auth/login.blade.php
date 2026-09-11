@extends('layouts.base')

@section('title', 'ログイン')

@section('content')
    <div class="mx-auto max-w-sm">
        <h1 class="mb-6 text-xl font-bold text-stone-900">ログイン</h1>

        @if ($errors->any())
            <div class="mb-4 space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="post" class="space-y-4 rounded-xl border border-stone-200 bg-white p-6">
            @csrf

            <label class="block">
                <span class="mb-1 block text-sm font-medium text-stone-700">メールアドレス</span>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
            </label>

            <label class="block">
                <span class="mb-1 block text-sm font-medium text-stone-700">パスワード</span>
                <input type="password" name="password"
                    class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
            </label>

            <button type="submit"
                class="w-full rounded-lg bg-amber-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-amber-700">
                ログイン
            </button>
        </form>

        <a href="/register" class="mt-4 block text-center text-sm text-amber-700 hover:underline">会員登録はこちら</a>
    </div>
@endsection
