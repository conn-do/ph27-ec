@extends('layouts.base')

@section('title', 'カート')

@section('content')
    <x-page-heading emoji="🛒" color="bg-pop-orange">
        カートの なかみ
        <x-slot:description>かずを かえたり、いらないものを だしたり できるよ。</x-slot:description>
    </x-page-heading>

    @if ($calculation['lines'] === [])
        <x-empty-state emoji="🛒" :action-label="'おみせに いく'" :action-href="route('home')">
            カートは まだ からっぽだよ。
        </x-empty-state>
    @else
        <div class="grid gap-6 lg:grid-cols-[1fr_20rem] lg:items-start">
            {{-- しょうひんの リスト --}}
            <ul class="space-y-4">
                @foreach ($calculation['lines'] as $line)
                    @php($product = $line['product'])
                    <li class="rounded-3xl border-4 border-ink bg-white p-4 shadow-block">
                        <div class="flex gap-4">
                            <a href="{{ route('products.show', $product) }}"
                                class="h-24 w-24 shrink-0 overflow-hidden rounded-2xl border-4 border-ink bg-paper-deep">
                                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}"
                                    class="h-full w-full object-contain p-1">
                            </a>

                            <div class="min-w-0 grow">
                                <a href="{{ route('products.show', $product) }}"
                                    class="text-lg font-black hover:underline">{{ $product->name }}</a>

                                <p class="mt-1 text-sm font-bold text-ink/60">
                                    1こ {{ number_format($line['unit_price']) }}えん（ぜい {{ $line['tax_rate'] }}%）
                                </p>

                                @unless ($line['is_purchasable'])
                                    <p class="mt-2 rounded-xl bg-pop-red px-3 py-1 text-sm font-black text-white">
                                        のこり {{ $line['stock'] }}こ しか ないよ
                                    </p>
                                @endunless

                                <div class="mt-3 flex flex-wrap items-center gap-3">
                                    <form action="{{ route('cart.update', $product) }}" method="POST"
                                        class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <label class="text-sm font-black" for="quantity-{{ $product->id }}">こすう</label>
                                        <select id="quantity-{{ $product->id }}" name="quantity" onchange="this.form.submit()"
                                            class="rounded-xl border-4 border-ink bg-white px-3 py-2 text-lg font-black focus:ring-4 focus:ring-pop-blue focus:outline-none">
                                            @for ($i = 1; $i <= max($product->maxPurchasableQuantity(), $line['quantity']); $i++)
                                                <option value="{{ $i }}" @selected($line['quantity'] === $i)>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </form>

                                    <form action="{{ route('cart.destroy', $product) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="rounded-xl border-4 border-ink bg-white px-3 py-2 text-sm font-black shadow-block-sm block-press">
                                            🗑 だす
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="shrink-0 text-right">
                                <p class="calc-number text-2xl">{{ number_format($line['subtotal']) }}</p>
                                <p class="text-xs font-bold text-ink/60">えん（ぜいぬき）</p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            {{-- ごうけい --}}
            <aside class="sticky top-24 rounded-3xl border-4 border-ink bg-white p-5 shadow-block">
                <p class="text-sm font-black text-ink/60">ぜんぶで {{ $calculation['total_quantity'] }}こ</p>

                <dl class="mt-3 space-y-2">
                    <div class="flex items-baseline justify-between gap-2">
                        <dt class="text-sm font-black">ぜいぬき</dt>
                        <dd class="calc-number text-xl">{{ number_format($calculation['subtotal']) }}えん</dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-2">
                        <dt class="text-sm font-black">しょうひぜい</dt>
                        <dd class="calc-number text-xl">{{ number_format($calculation['tax_total']) }}えん</dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-2 border-t-4 border-dashed border-ink/20 pt-3">
                        <dt class="text-base font-black">おかいけい</dt>
                        <dd class="calc-number text-3xl text-pop-red">{{ number_format($calculation['total']) }}えん</dd>
                    </div>
                </dl>

                <div class="mt-5 space-y-3">
                    @auth
                        <x-big-button :href="route('checkout.show')" color="bg-pop-green" class="w-full"
                            :disabled="! $calculation['is_purchasable']">
                            レジに すすむ →
                        </x-big-button>
                    @else
                        <x-big-button :href="route('login')" color="bg-pop-blue" class="w-full">
                            ログインして かう
                        </x-big-button>
                    @endauth

                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full rounded-2xl border-4 border-ink bg-white px-4 py-2 text-sm font-black shadow-block-sm block-press">
                            カートを からっぽに する
                        </button>
                    </form>
                </div>
            </aside>
        </div>

        {{-- けいさんの しくみ --}}
        <section class="mt-10">
            <h2 class="mb-4 flex items-center gap-2 text-2xl font-black">
                <span aria-hidden="true">🧮</span> どうやって この きんがくに なるの？
            </h2>
            <x-calc-panel :calculation="$calculation" :tax-labels="config('shop.tax.labels')" />
        </section>
    @endif
@endsection
