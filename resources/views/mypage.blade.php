@extends('layouts.base')

@section('title', 'マイページ')

@section('content')
    <div class="max-w-3xl mx-auto py-8">

        <!-- Header -->
        <div class="mb-8 border-b border-neutral-200 pb-4">
            <h1 class="text-2xl font-light tracking-tight text-neutral-900 uppercase">
                MY PAGE
            </h1>
        </div>

        <!-- Success Notification -->
        @if (session('message'))
            <div class="mb-6 bg-emerald-50 border-l-2 border-emerald-500 p-4">
                <p class="text-xs text-emerald-700 font-medium">{{ session('message') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Profile Overview Card -->
            <div class="bg-white border border-neutral-200 p-6 flex flex-col justify-between">
                <div>
                    <h2
                        class="text-xs font-semibold uppercase tracking-widest text-neutral-400 border-b border-neutral-100 pb-3 mb-4">
                        ACCOUNT DETAILS
                    </h2>

                    <div class="space-y-3 mb-6">
                        <div>
                            <span
                                class="text-[10px] uppercase font-semibold text-neutral-400 block tracking-wider">お名前</span>
                            <p class="text-sm font-medium text-neutral-900">{{ auth()->user()->name }}</p>
                        </div>
                        <div>
                            <span
                                class="text-[10px] uppercase font-semibold text-neutral-400 block tracking-wider">メールアドレス</span>
                            <p class="text-sm font-medium text-neutral-900">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Edit Profile Button -->
                <a href="{{ route('profile.edit_form') }}"
                    class="block text-center bg-black text-white py-2.5 text-xs font-semibold uppercase tracking-widest hover:bg-neutral-800 transition">
                    プロフィールを編集する
                </a>
            </div>

            <!-- Order History Card -->
            <div class="bg-white border border-neutral-200 p-6 flex flex-col justify-between">
                <div>
                    <h2
                        class="text-xs font-semibold uppercase tracking-widest text-neutral-400 border-b border-neutral-100 pb-3 mb-4">
                        ORDER HISTORY
                    </h2>
                    <p class="text-xs text-neutral-500 leading-relaxed mb-6">
                        過去のご注文内容や配送状況をご確認いただけます。
                    </p>
                </div>

                <!-- Order History Link Button -->
                <a href="/orders"
                    class="block text-center border border-neutral-300 bg-white text-neutral-900 py-2.5 text-xs font-semibold uppercase tracking-widest hover:bg-neutral-50 transition">
                    注文履歴を見る &rarr;
                </a>
            </div>

        </div>

    </div>
@endsection
