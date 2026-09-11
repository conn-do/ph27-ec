@extends('layouts.base')

@section('title', $product->name)

@section('content')
    <a href="{{ route('home') }}"
        class="mb-4 inline-block text-sm font-black underline decoration-4 decoration-pop-blue underline-offset-4">
        ← おみせに もどる
    </a>

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- しゃしん --}}
        <div class="overflow-hidden rounded-3xl border-4 border-ink bg-white shadow-block">
            <div class="aspect-square bg-paper-deep">
                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="h-full w-full object-contain p-6">
            </div>
        </div>

        {{-- なまえと ねだん --}}
        <div class="flex flex-col gap-4">
            @if ($product->category)
                <a href="{{ route('home', ['category' => $product->category->slug]) }}"
                    class="w-fit rounded-full border-4 border-ink bg-white px-4 py-1 text-sm font-black shadow-block-sm block-press">
                    {{ $product->category->emoji }} {{ $product->category->name }}
                </a>
            @endif

            <h1 class="text-3xl leading-tight font-black sm:text-4xl">{{ $product->name }}</h1>

            <div class="flex flex-wrap items-center gap-3">
                <x-stock-badge :product="$product" />
                @if ($product->reviews_count)
                    <x-star-rating :rating="$product->reviews_avg_rating" :count="$product->reviews_count" />
                @endif
            </div>

            {{-- ねだんの けいさん を その場で 見せる --}}
            <div class="rounded-3xl border-4 border-ink bg-white p-5 shadow-block">
                <p class="mb-3 text-sm font-black text-ink/60">この しょうひんの ねだん</p>

                <div class="space-y-2">
                    <div class="flex items-baseline justify-between gap-3">
                        <span class="text-sm font-black">ほんたいの ねだん</span>
                        <span class="calc-number text-2xl">{{ number_format($product->price) }}えん</span>
                    </div>
                    <div class="flex items-baseline justify-between gap-3">
                        <span class="text-sm font-black">
                            しょうひぜい {{ $product->tax_rate }}%
                            <span class="font-bold text-ink/50">
                                （{{ number_format($product->price) }} × {{ $product->tax_rate }} ÷ 100）
                            </span>
                        </span>
                        <span class="calc-number text-2xl">{{ number_format($product->taxFor()) }}えん</span>
                    </div>
                    <div class="flex items-baseline justify-between gap-3 border-t-4 border-dashed border-ink/20 pt-3">
                        <span class="text-base font-black">はらう ねだん</span>
                        <span class="calc-number text-4xl text-pop-red">
                            {{ number_format($product->priceWithTax()) }}えん
                        </span>
                    </div>
                </div>
            </div>

            <p class="text-base leading-relaxed font-bold">{{ $product->description }}</p>

            {{-- カートに いれる --}}
            @if ($product->isInStock())
                <form action="{{ route('cart.store') }}" method="POST" class="flex flex-wrap items-center gap-3">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <label class="flex items-center gap-2 text-base font-black">
                        こすう
                        <select name="quantity"
                            class="rounded-2xl border-4 border-ink bg-white px-4 py-3 text-xl font-black focus:ring-4 focus:ring-pop-blue focus:outline-none">
                            @for ($i = 1; $i <= $product->maxPurchasableQuantity(); $i++)
                                <option value="{{ $i }}" @selected(old('quantity', 1) == $i)>{{ $i }}</option>
                            @endfor
                        </select>
                    </label>

                    <x-big-button color="bg-pop-orange">🛒 カートに いれる</x-big-button>
                </form>
            @else
                <x-big-button :disabled="true" color="bg-ink">いまは うりきれ です</x-big-button>
            @endif

            {{-- おきにいり --}}
            @auth
                <form action="{{ route('favorites.store', $product) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-2 rounded-2xl border-4 border-ink px-5 py-3 text-base font-black shadow-block-sm block-press {{ $isFavorited ? 'bg-pop-pink text-white' : 'bg-white' }}">
                        {{ $isFavorited ? '💖 おきにいり ちゅう' : '🤍 おきにいりに いれる' }}
                    </button>
                </form>
            @endauth
        </div>
    </div>

    {{-- かんそう --}}
    <section class="mt-12">
        <h2 class="mb-4 flex items-center gap-2 text-2xl font-black">
            <span aria-hidden="true">💬</span> みんなの かんそう
        </h2>

        @auth
            @if ($canReview)
                <form action="{{ route('reviews.store', $product) }}" method="POST"
                    class="mb-6 rounded-3xl border-4 border-ink bg-white p-5 shadow-block">
                    @csrf

                    <p class="mb-3 text-base font-black">ほしの かず を えらんでね</p>
                    <div class="mb-4 flex gap-2">
                        @for ($i = 5; $i >= 1; $i--)
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}" class="peer sr-only"
                                    @checked(old('rating', $myReview?->rating) == $i)>
                                <span
                                    class="flex h-12 w-12 items-center justify-center rounded-2xl border-4 border-ink bg-white text-lg font-black peer-checked:bg-pop-yellow">
                                    {{ $i }}★
                                </span>
                            </label>
                        @endfor
                    </div>

                    <textarea name="comment" rows="3" maxlength="300" placeholder="つかってみて どうだった？"
                        class="mb-4 w-full rounded-2xl border-4 border-ink px-4 py-3 text-base font-bold placeholder:text-ink/40 focus:ring-4 focus:ring-pop-blue focus:outline-none">{{ old('comment', $myReview?->comment) }}</textarea>

                    <div class="flex flex-wrap items-center gap-3">
                        <x-big-button color="bg-pop-green">
                            {{ $myReview ? 'かんそうを かきなおす' : 'かんそうを おくる' }}
                        </x-big-button>
                    </div>
                </form>

                @if ($myReview)
                    <form action="{{ route('reviews.destroy', $product) }}" method="POST" class="mb-6">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="rounded-2xl border-4 border-ink bg-white px-4 py-2 text-sm font-black shadow-block-sm block-press">
                            🗑 じぶんの かんそうを けす
                        </button>
                    </form>
                @endif
            @else
                <p class="mb-6 rounded-2xl border-4 border-dashed border-ink/30 bg-white/60 px-5 py-4 text-base font-bold">
                    かんそうは、この しょうひんを かったひと だけ かけるよ。
                </p>
            @endif
        @endauth

        @forelse ($reviews as $review)
            <article class="mb-3 rounded-3xl border-4 border-ink bg-white p-5 shadow-block-sm">
                <div class="mb-2 flex flex-wrap items-center gap-3">
                    <x-star-rating :rating="$review->rating" />
                    <span class="text-sm font-black">{{ $review->user->name }}</span>
                    <span class="text-xs font-bold text-ink/50">{{ $review->created_at->format('Y/n/j') }}</span>
                </div>
                @if ($review->comment)
                    <p class="text-base leading-relaxed font-bold">{{ $review->comment }}</p>
                @endif
            </article>
        @empty
            <x-empty-state emoji="💭">まだ かんそうが ないよ。</x-empty-state>
        @endforelse
    </section>

    {{-- にた しょうひん --}}
    @if ($relatedProducts->isNotEmpty())
        <section class="mt-12">
            <h2 class="mb-4 flex items-center gap-2 text-2xl font-black">
                <span aria-hidden="true">✨</span> こんなのも あるよ
            </h2>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                @foreach ($relatedProducts as $related)
                    <x-product-card :product="$related" />
                @endforeach
            </div>
        </section>
    @endif
@endsection
