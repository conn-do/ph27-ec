@extends('layouts.base')

@section('title', 'プロフィール編集')

@section('content')
    <div class="max-w-xl mx-auto py-8">

        <div class="mb-8">
            <a href="/mypage"
                class="text-xs font-semibold uppercase tracking-widest text-neutral-400 hover:text-black transition">
                &larr; MY PAGE
            </a>
        </div>

        <div class="bg-white border border-neutral-200 p-8">

            <h1 class="text-xl font-light tracking-tight text-neutral-900 uppercase border-b border-neutral-100 pb-4 mb-6">
                EDIT PROFILE
            </h1>

            @if (session('message'))
                <div class="mb-6 bg-emerald-50 border-l-2 border-emerald-500 p-3">
                    <p class="text-xs text-emerald-700 font-medium">{{ session('message') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-2 border-red-500 p-3 space-y-1">
                    @foreach ($errors->all() as $error)
                        <p class="text-xs text-red-600 font-medium">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('profile.update_data') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Account Info -->
                <div>
                    <label for="name"
                        class="block text-xs font-semibold uppercase tracking-wider text-neutral-700 mb-2">
                        お名前
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-2.5 border border-neutral-300 text-xs focus:outline-none focus:border-black bg-white">
                </div>

                <div>
                    <label for="email"
                        class="block text-xs font-semibold uppercase tracking-wider text-neutral-700 mb-2">
                        メールアドレス
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-2.5 border border-neutral-300 text-xs focus:outline-none focus:border-black bg-white">
                </div>

                <!-- Password Change Section -->
                <div class="border-t border-neutral-200 pt-6 mt-6 space-y-6">
                    <h2 class="text-xs font-semibold uppercase tracking-widest text-neutral-400">
                        CHANGE PASSWORD <span class="font-normal text-neutral-400">（変更する場合のみ入力）</span>
                    </h2>

                    <div>
                        <label for="current_password"
                            class="block text-xs font-semibold uppercase tracking-wider text-neutral-700 mb-2">
                            現在のパスワード
                        </label>
                        <input type="password" id="current_password" name="current_password"
                            class="w-full px-4 py-2.5 border border-neutral-300 text-xs focus:outline-none focus:border-black bg-white">
                    </div>

                    <div>
                        <label for="new_password"
                            class="block text-xs font-semibold uppercase tracking-wider text-neutral-700 mb-2">
                            新しいパスワード
                        </label>
                        <input type="password" id="new_password" name="new_password"
                            class="w-full px-4 py-2.5 border border-neutral-300 text-xs focus:outline-none focus:border-black bg-white">
                    </div>

                    <div>
                        <label for="new_password_confirmation"
                            class="block text-xs font-semibold uppercase tracking-wider text-neutral-700 mb-2">
                            新しいパスワード（確認）
                        </label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                            class="w-full px-4 py-2.5 border border-neutral-300 text-xs focus:outline-none focus:border-black bg-white">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-black text-white py-3.5 text-xs font-semibold uppercase tracking-widest hover:bg-neutral-800 transition cursor-pointer">
                    更新する
                </button>
            </form>

        </div>

    </div>
@endsection
