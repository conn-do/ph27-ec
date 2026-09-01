@extends('layouts.base')

@section('title', 'ログイン')

@section('content')
    <div class="max-w-md mx-auto py-12">
        <div class="bg-white border border-neutral-200 p-8 shadow-sm">

            <!-- Heading -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-light tracking-tight text-neutral-900 uppercase">LOGIN</h1>
                <p class="text-xs text-neutral-400 uppercase tracking-widest mt-1">ログインアカウント情報を入力してください</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-2 border-red-500 p-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <p class="text-xs text-red-600 font-medium">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="email"
                        class="block text-xs font-semibold uppercase tracking-wider text-neutral-700 mb-2">
                        メールアドレス
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-2.5 border border-neutral-300 text-xs focus:outline-none focus:border-black bg-white transition"
                        placeholder="example@email.com">
                </div>

                <div>
                    <label for="password"
                        class="block text-xs font-semibold uppercase tracking-wider text-neutral-700 mb-2">
                        パスワード
                    </label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2.5 border border-neutral-300 text-xs focus:outline-none focus:border-black bg-white transition">
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-black text-white py-3 text-xs font-semibold uppercase tracking-widest hover:bg-neutral-800 transition cursor-pointer">
                        ログイン
                    </button>
                </div>
            </form>

            <!-- Register Link -->
            <div class="mt-8 border-t border-neutral-200 pt-6 text-center text-xs text-neutral-500">
                アカウントをお持ちでない方はこちら
                <a href="/register" class="text-black font-semibold hover:underline ml-1">会員登録はこちら</a>
            </div>

        </div>
    </div>
@endsection
