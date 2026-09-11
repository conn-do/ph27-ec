@extends('layouts.base')

@section('title', 'おきにいり')

@section('content')
    <x-page-heading emoji="💖" color="bg-pop-pink">
        おきにいり
        <x-slot:description>きになる しょうひんを ためて おけるよ。</x-slot:description>
    </x-page-heading>

    @if ($products->isEmpty())
        <x-empty-state emoji="🤍" :action-label="'おみせに いく'" :action-href="route('home')">
            まだ おきにいりが ないよ。
        </x-empty-state>
    @else
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($products as $product)
                <div class="flex flex-col gap-2">
                    <x-product-card :product="$product" />

                    <form action="{{ route('favorites.destroy', $product) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full rounded-2xl border-4 border-ink bg-white px-3 py-2 text-xs font-black shadow-block-sm block-press">
                            おきにいりから はずす
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @endif
@endsection
