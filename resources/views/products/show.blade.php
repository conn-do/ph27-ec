@extends('layouts.base')

@section('title', $product->name)

@section('content')
    <div class="grid gap-8 md:grid-cols-2">
        <div class="overflow-hidden rounded-xl border border-stone-200 bg-stone-100">
            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
        </div>

        <div>
            <div class="flex items-start justify-between gap-3">
                <a href="/categories/{{ $product->category->slug }}"
                    class="inline-block rounded-full bg-stone-100 px-3 py-1 text-xs font-medium text-stone-600 hover:bg-stone-200">
                    {{ $product->category->name }}
                </a>

                @auth
                    <form
                        action="/products/{{ $product->id }}/{{ $isFavorited ? 'unfavorite' : 'favorite' }}"
                        method="POST">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-medium transition {{ $isFavorited ? 'border-red-200 bg-red-50 text-red-600' : 'border-stone-200 text-stone-500 hover:bg-stone-100' }}"
                            aria-label="お気に入り">
                            <svg class="h-4 w-4 {{ $isFavorited ? 'fill-red-500' : 'fill-none stroke-current' }}"
                                viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 20.5s-7-4.35-9.5-8.5C.8 8.5 2.5 5 6 5c2 0 3.5 1 6 3.5C14.5 6 16 5 18 5c3.5 0 5.2 3.5 3.5 7-2.5 4.15-9.5 8.5-9.5 8.5z" />
                            </svg>
                            {{ $isFavorited ? 'お気に入り済み' : 'お気に入り' }}
                        </button>
                    </form>
                @endauth
            </div>

            <h1 class="mt-3 text-2xl font-bold text-stone-900">{{ $product->name }}</h1>

            @if ($reviews->isNotEmpty())
                <div class="mt-1 flex items-center gap-2">
                    <x-star-rating :rating="$averageRating" />
                    <span class="text-sm text-stone-500">{{ $averageRating }}（{{ $reviews->count() }}件のレビュー）</span>
                </div>
            @endif

            <p class="mt-2 text-2xl font-bold text-amber-700">{{ number_format($product->price) }}円</p>

            @if ($product->stock <= 0)
                <span class="mt-3 inline-block rounded-full bg-stone-200 px-3 py-1 text-xs font-medium text-stone-600">
                    売り切れ
                </span>
            @elseif ($product->stock <= 5)
                <span class="mt-3 inline-block rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                    残りわずか（あと{{ $product->stock }}個）
                </span>
            @else
                <span class="mt-3 inline-block rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">
                    在庫あり
                </span>
            @endif

            <p class="mt-4 text-sm leading-relaxed text-stone-600">{{ $product->description }}</p>

            @if ($errors->any())
                <div class="mt-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <p class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                            {{ $error }}
                        </p>
                    @endforeach
                </div>
            @endif

            @if ($product->stock > 0)
                <form action="/cart" method="POST" class="mt-6 flex items-end gap-3">
                    @csrf
                    <div>
                        <label class="mb-1 block text-xs font-medium text-stone-600">個数</label>
                        <input type="number" name="quantity" min="1" max="10" value="{{ old('quantity', 1) }}"
                            class="w-20 rounded-lg border border-stone-300 px-3 py-2 text-sm @error('quantity') border-red-400 @enderror focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                    </div>
                    <input type="hidden" name="productId" value="{{ $product->id }}">
                    <button type="submit"
                        class="rounded-lg bg-amber-600 px-6 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-amber-700">
                        カートに入れる
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="mt-12 max-w-2xl">
        <h2 class="mb-4 text-lg font-bold text-stone-900">レビュー</h2>

        @auth
            <form action="/products/{{ $product->id }}/reviews" method="POST"
                class="mb-8 rounded-xl border border-stone-200 bg-white p-5">
                @csrf

                <label class="mb-3 block">
                    <span class="mb-1 block text-sm font-medium text-stone-700">評価</span>
                    <select name="rating"
                        class="rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" @selected(old('rating', $myReview->rating ?? 5) == $i)>
                                {{ $i }} - {{ str_repeat('★', $i) }}
                            </option>
                        @endfor
                    </select>
                </label>

                <label class="mb-3 block">
                    <span class="mb-1 block text-sm font-medium text-stone-700">コメント（任意）</span>
                    <textarea name="comment" rows="3"
                        class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">{{ old('comment', $myReview->comment ?? '') }}</textarea>
                </label>

                <div class="flex items-center gap-3">
                    <button type="submit"
                        class="rounded-lg bg-amber-600 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-amber-700">
                        {{ $myReview ? 'レビューを更新する' : 'レビューを投稿する' }}
                    </button>

                    @if ($myReview)
                        <form action="/reviews/{{ $myReview->id }}/delete" method="POST"
                            onsubmit="return confirm('レビューを削除しますか？');">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:underline">
                                削除する
                            </button>
                        </form>
                    @endif
                </div>
            </form>
        @else
            <p class="mb-8 text-sm text-stone-500">
                レビューを投稿するには<a href="{{ route('login') }}" class="text-amber-700 hover:underline">ログイン</a>してください。
            </p>
        @endauth

        @if ($reviews->isEmpty())
            <p class="text-sm text-stone-500">まだレビューがありません。</p>
        @else
            <ul class="space-y-4">
                @foreach ($reviews as $review)
                    <li class="rounded-xl border border-stone-200 bg-white p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <x-star-rating :rating="$review->rating" />
                                <span class="text-sm font-medium text-stone-800">{{ $review->user->name }}</span>
                            </div>
                            <span class="text-xs text-stone-400">{{ $review->created_at->format('Y/m/d') }}</span>
                        </div>
                        @if ($review->comment)
                            <p class="mt-2 text-sm text-stone-600">{{ $review->comment }}</p>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
