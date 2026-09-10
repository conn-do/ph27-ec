@extends('layouts.base')

@section('title', $product->name)

@section('content')
    <div class="mx-auto max-w-7xl px-5 py-10 lg:px-8 lg:py-16">
        <a href="{{ route('home') }}" class="text-sm font-bold text-stone-500 hover:text-teal-800">← 商品一覧へ戻る</a>

        <section class="mt-7 grid gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="overflow-hidden rounded-[2.5rem] bg-stone-100">
                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="aspect-square h-full w-full object-cover">
            </div>
            <div class="flex flex-col justify-center">
                <a href="{{ route('categories.show', $product->category) }}" class="text-xs font-black tracking-[0.25em] text-teal-700">{{ $product->category->name }}</a>
                <h1 class="mt-4 font-serif text-4xl font-black leading-tight md:text-5xl">{{ $product->name }}</h1>
                <p class="mt-6 text-3xl font-black">¥{{ number_format($product->price) }}<span class="ml-2 text-sm font-medium text-stone-500">税込</span></p>
                <p class="mt-7 leading-8 text-stone-600">{{ $product->description }}</p>

                <div class="mt-7 flex items-center gap-3">
                    @if ($product->stock <= 0)
                        <span class="rounded-full bg-stone-200 px-4 py-2 text-sm font-bold text-stone-500">売り切れ</span>
                    @elseif ($product->stock <= 5)
                        <span class="rounded-full bg-rose-100 px-4 py-2 text-sm font-bold text-rose-800">残り{{ $product->stock }}点</span>
                    @else
                        <span class="rounded-full bg-teal-50 px-4 py-2 text-sm font-bold text-teal-800">在庫あり</span>
                    @endif
                </div>

                @if ($errors->any())
                    <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-bold text-rose-800">
                        @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                    </div>
                @endif

                @if ($product->stock > 0)
                    <form action="{{ route('cart.store') }}" method="POST" class="mt-8 flex flex-wrap gap-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <label class="flex items-center gap-3 rounded-2xl border border-stone-300 bg-white px-4"><span class="text-sm font-bold">数量</span><input type="number" name="quantity" min="1" max="{{ min(10, $product->stock) }}" value="{{ old('quantity', 1) }}" class="w-16 border-0 bg-transparent py-3 text-center font-bold outline-none"></label>
                        <button class="flex-1 rounded-2xl bg-teal-800 px-6 py-4 font-black text-white hover:bg-teal-900">カートに入れる</button>
                    </form>
                @endif

                @auth
                    <form action="{{ $isFavorite ? route('favorites.destroy', $product) : route('favorites.store', $product) }}" method="POST" class="mt-3">
                        @csrf
                        @if ($isFavorite) @method('DELETE') @endif
                        <button class="w-full rounded-2xl border border-stone-300 bg-white px-6 py-3 font-bold hover:border-rose-400 hover:text-rose-700">{{ $isFavorite ? '♥ お気に入りから外す' : '♡ お気に入りに追加' }}</button>
                    </form>
                @endauth
            </div>
        </section>

        <section class="mt-20 grid gap-10 lg:grid-cols-[0.8fr_1.2fr]">
            <div>
                <p class="text-xs font-black tracking-[0.3em] text-amber-700">REVIEWS</p>
                <h2 class="mt-3 font-serif text-3xl font-black">使った人の声</h2>
                <div class="mt-5 flex items-center gap-3">
                    <span class="text-3xl text-amber-500">★</span>
                    <strong class="text-2xl">{{ number_format((float) $product->reviews->avg('rating'), 1) }}</strong>
                    <span class="text-sm text-stone-500">{{ $product->reviews->count() }}件のレビュー</span>
                </div>

                @auth
                    <form action="{{ route('reviews.store', $product) }}" method="POST" class="mt-8 rounded-3xl border border-stone-200 bg-white p-6">
                        @csrf
                        <h3 class="font-bold">{{ $userReview ? 'レビューを更新' : 'レビューを書く' }}</h3>
                        <label class="mt-4 block text-sm font-bold">評価
                            <select name="rating" class="mt-2 w-full rounded-xl border border-stone-300 bg-white px-4 py-3">
                                @foreach ([5 => '★★★★★ とても良い', 4 => '★★★★☆ 良い', 3 => '★★★☆☆ 普通', 2 => '★★☆☆☆ いまひとつ', 1 => '★☆☆☆☆ 良くない'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('rating', $userReview?->rating ?? 5) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="mt-4 block text-sm font-bold">コメント
                            <textarea name="comment" rows="4" maxlength="500" class="mt-2 w-full rounded-xl border border-stone-300 px-4 py-3" placeholder="使い心地を教えてください">{{ old('comment', $userReview?->comment) }}</textarea>
                        </label>
                        <button class="mt-4 w-full rounded-xl bg-stone-900 px-5 py-3 text-sm font-black text-white hover:bg-teal-800">レビューを保存</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="mt-7 inline-flex rounded-full border border-stone-300 bg-white px-5 py-3 text-sm font-bold">ログインしてレビューを書く</a>
                @endauth
            </div>
            <div class="space-y-4">
                @forelse ($product->reviews as $review)
                    <article class="rounded-3xl border border-stone-200 bg-white p-6">
                        <div class="flex items-center justify-between gap-4"><strong>{{ $review->user->name }}</strong><time class="text-xs text-stone-400">{{ $review->created_at->format('Y.m.d') }}</time></div>
                        <p class="mt-2 tracking-wider text-amber-500">{{ str_repeat('★', $review->rating) }}<span class="text-stone-300">{{ str_repeat('★', 5 - $review->rating) }}</span></p>
                        <p class="mt-4 leading-7 text-stone-600">{{ $review->comment }}</p>
                    </article>
                @empty
                    <div class="rounded-3xl border border-dashed border-stone-300 bg-white px-6 py-16 text-center text-stone-500">まだレビューはありません。最初の感想を届けてください。</div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
