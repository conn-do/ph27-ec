@extends('layouts.base')

@section('title', 'マイページ')

@section('content')
    <h1 class="text-2xl font-light my-14">マイページ</h1>

    <div class="border border-navy-200 rounded-2xl divide-y divide-navy-200 mb-20">
        <a href="/orders" class="flex items-center justify-between px-6 py-5 text-sm text-navy-950 hover:bg-navy-100 transition">
            注文履歴
            <span class="text-navy-500">›</span>
        </a>
    </div>
@endsection