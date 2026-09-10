@extends('layouts.base')
@section('title', '会員登録')
@section('content')
    <section class="relative overflow-hidden bg-amber-50 px-5 py-16 lg:py-24">
        <div class="absolute -right-20 top-20 size-72 rounded-full bg-amber-300/40 blur-3xl"></div>
        <div class="relative mx-auto max-w-2xl rounded-[2.5rem] border border-stone-200 bg-white p-7 shadow-2xl shadow-slate-900/10 sm:p-12">
            <p class="font-black tracking-[0.2em] text-amber-600">JOIN US</p><h1 class="mt-2 text-4xl font-black tracking-tight">会員登録</h1><p class="mt-2 text-slate-500">必要な情報を入力して、文房具選びを始めましょう。</p>
            <form action="{{ route('register.store') }}" method="POST" class="mt-8 grid gap-5">@csrf
                <div><label for="name" class="text-sm font-black">お名前</label><input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" class="mt-2 w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3.5 outline-none ring-amber-500 focus:border-amber-500 focus:ring-2">@error('name')<p class="mt-2 text-sm font-bold text-rose-600">{{ $message }}</p>@enderror</div>
                <div><label for="email" class="text-sm font-black">メールアドレス</label><input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="mt-2 w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3.5 outline-none ring-amber-500 focus:border-amber-500 focus:ring-2">@error('email')<p class="mt-2 text-sm font-bold text-rose-600">{{ $message }}</p>@enderror</div>
                <div class="grid gap-5 sm:grid-cols-2"><div><label for="password" class="text-sm font-black">パスワード</label><input id="password" name="password" type="password" required autocomplete="new-password" class="mt-2 w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3.5 outline-none ring-amber-500 focus:border-amber-500 focus:ring-2">@error('password')<p class="mt-2 text-sm font-bold text-rose-600">{{ $message }}</p>@enderror</div><div><label for="password_confirmation" class="text-sm font-black">パスワード（確認）</label><input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="mt-2 w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3.5 outline-none ring-amber-500 focus:border-amber-500 focus:ring-2"></div></div>
                <button type="submit" class="mt-2 w-full rounded-full bg-slate-950 px-6 py-4 font-black text-white transition hover:bg-amber-600">アカウントを作成</button>
            </form>
            <p class="mt-7 text-center text-sm text-slate-500">すでに登録済みですか？ <a href="{{ route('login') }}" class="font-black text-amber-700 underline underline-offset-4">ログイン</a></p>
        </div>
    </section>
@endsection
