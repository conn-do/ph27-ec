@extends('layouts.base')

@section('title', '会員登録')

@section('content')
    <div class="max-w-md mx-auto py-12">
        <div class="bg-white border border-neutral-200 p-8 shadow-sm">

            <!-- Heading -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-light tracking-tight text-neutral-900 uppercase">REGISTER</h1>
                <p class="text-xs text-neutral-400 uppercase tracking-widest mt-1">新規アカウントを作成してください</p>
            </div>

            <!-- General Error Messages -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-2 border-red-500 p-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <p class="text-xs text-red-600 font-medium">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Registration Form -->
            <form action="{{ route('register.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name"
                        class="block text-xs font-semibold uppercase tracking-wider text-neutral-700 mb-2">
                        お名前
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-2.5 border border-neutral-300 text-xs focus:outline-none focus:border-black bg-white transition"
                        placeholder="山田 太郎">
                </div>

                <!-- Email -->
                <div>
                    <label for="email"
                        class="block text-xs font-semibold uppercase tracking-wider text-neutral-700 mb-2">
                        メールアドレス
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 border @error('email') border-red-500 @else border-neutral-300 @enderror text-xs focus:outline-none focus:border-black bg-white transition"
                        placeholder="example@email.com">
                    @error('email')
                        <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password"
                        class="block text-xs font-semibold uppercase tracking-wider text-neutral-700 mb-2">
                        パスワード
                    </label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2.5 border border-neutral-300 text-xs focus:outline-none focus:border-black bg-white transition">
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation"
                        class="block text-xs font-semibold uppercase tracking-wider text-neutral-700 mb-2">
                        パスワード（確認）
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-4 py-2.5 border border-neutral-300 text-xs focus:outline-none focus:border-black bg-white transition">
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-black text-white py-3 text-xs font-semibold uppercase tracking-widest hover:bg-neutral-800 transition cursor-pointer">
                        登録
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="mt-8 border-t border-neutral-200 pt-6 text-center text-xs text-neutral-500">
                すでにアカウントをお持ちですか？
                <a href="/login" class="text-black font-semibold hover:underline ml-1">ログインはこちら</a>
            </div>

        </div>
    </div>
@endsection
