@extends('layouts.base')
@section('title', 'ログイン')
@section('content')
    <section class="relative overflow-hidden px-5 py-16 lg:py-24">
        <div class="absolute inset-0 -z-10 bg-amber-50"></div>
        <div class="absolute -left-20 top-24 -z-10 size-72 rounded-full bg-amber-300/40 blur-3xl"></div>
        <div class="mx-auto grid max-w-5xl overflow-hidden rounded-[2.5rem] border border-stone-200 bg-white shadow-2xl shadow-slate-900/10 lg:grid-cols-[0.85fr_1.15fr]">
            <div class="hidden bg-slate-950 p-12 text-white lg:flex lg:flex-col lg:justify-between">
                <div><p class="text-sm font-black tracking-[0.25em] text-amber-400">WELCOME BACK</p><h1 class="mt-4 text-4xl font-black leading-tight">お気に入りの文房具を、<br>あなたの毎日に。</h1><p class="mt-5 leading-7 text-slate-300">ログインすると、カートの商品をそのまま注文できます。</p></div>
                <div class="text-7xl" aria-hidden="true">✎</div>
            </div>
            <div class="p-7 sm:p-12">
                <p class="font-black text-amber-600 lg:hidden">WELCOME BACK</p><h2 class="mt-1 text-4xl font-black tracking-tight">ログイン</h2><p class="mt-2 text-slate-500">登録したメールアドレスでログインしてください。</p>
                @if ($status)<div class="mt-6 rounded-2xl bg-emerald-50 px-4 py-3 font-bold text-emerald-700" role="status">{{ $status }}</div>@endif
                <form action="{{ route('login.store') }}" method="POST" class="mt-8 space-y-5">@csrf
                    <div><label for="email" class="text-sm font-black">メールアドレス</label><input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="you@example.com" class="mt-2 w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3.5 outline-none ring-amber-500 focus:border-amber-500 focus:ring-2">@error('email')<p class="mt-2 text-sm font-bold text-rose-600">{{ $message }}</p>@enderror</div>
                    <div><div class="flex items-center justify-between gap-4"><label for="password" class="text-sm font-black">パスワード</label>@if ($canResetPassword)<a href="{{ route('password.request') }}" class="text-sm font-bold text-amber-700 hover:underline">忘れた方はこちら</a>@endif</div><input id="password" name="password" type="password" required autocomplete="current-password" class="mt-2 w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3.5 outline-none ring-amber-500 focus:border-amber-500 focus:ring-2">@error('password')<p class="mt-2 text-sm font-bold text-rose-600">{{ $message }}</p>@enderror</div>
                    <label class="flex cursor-pointer items-center gap-3 text-sm font-bold text-slate-600"><input name="remember" type="checkbox" value="1" class="size-4 rounded border-stone-300 accent-amber-500">ログイン状態を保存する</label>
                    <button type="submit" class="w-full rounded-full bg-slate-950 px-6 py-4 font-black text-white transition hover:bg-amber-600">ログイン</button>
                </form>
                @if ($canRegister)<p class="mt-7 text-center text-sm text-slate-500">アカウントをお持ちでないですか？ <a href="{{ route('register') }}" class="font-black text-amber-700 underline underline-offset-4">会員登録</a></p>@endif
            </div>
        </div>
    </section>
@endsection
