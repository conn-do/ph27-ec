@extends('layouts.base')

@section('title', 'ご配送先の入力')

@section('content')
    <h1 class="mb-6 text-xl font-bold text-stone-900">ご配送先の入力</h1>

    @if ($errors->any())
        <div class="mb-4 space-y-1">
            @foreach ($errors->all() as $error)
                <p class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="/orders" method="post" class="max-w-md space-y-4 rounded-xl border border-stone-200 bg-white p-6">
        @csrf

        <label class="block">
            <span class="mb-1 block text-sm font-medium text-stone-700">お名前</span>
            <input type="text" name="shipping_name" value="{{ old('shipping_name') }}"
                class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
        </label>

        <label class="block">
            <span class="mb-1 block text-sm font-medium text-stone-700">郵便番号（例: 123-4567）</span>
            <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}"
                class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
        </label>

        <label class="block">
            <span class="mb-1 block text-sm font-medium text-stone-700">ご住所</span>
            <input type="text" name="shipping_address" value="{{ old('shipping_address') }}"
                class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
        </label>

        <label class="block">
            <span class="mb-1 block text-sm font-medium text-stone-700">電話番号</span>
            <input type="text" name="shipping_phone" value="{{ old('shipping_phone') }}"
                class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
        </label>

        <button type="submit"
            class="w-full rounded-lg bg-amber-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-amber-700">
            この内容で注文を確定する
        </button>
    </form>
@endsection
