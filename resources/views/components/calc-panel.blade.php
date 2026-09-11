{{--
    カートの中身から「いくらになるか」を じゅんばんに 見せる。
    CartCalculator が つくった はいれつを そのまま わたす。
--}}
@props([
    'calculation',
    'taxLabels' => [],
])

<div class="space-y-5">
    <x-calc-step number="1" title="ねだん × こすう を けいさんする" color="bg-pop-blue">
        <ul class="space-y-4">
            @foreach ($calculation['lines'] as $line)
                <li>
                    <p class="mb-1 text-sm font-black text-ink/70">{{ $line['product']->name }}</p>
                    <x-calc-formula :parts="[
                        number_format($line['unit_price']),
                        '×',
                        $line['quantity'] . 'こ',
                    ]" :answer="number_format($line['subtotal'])" />
                </li>
            @endforeach
        </ul>

        <div class="mt-5 border-t-4 border-dashed border-ink/20 pt-4">
            <p class="mb-1 text-sm font-black text-ink/70">ぜんぶ たすと（ぜいぬきの ねだん）</p>
            <x-calc-formula :parts="[
                collect($calculation['lines'])->map(fn ($line) => number_format($line['subtotal']))->implode(' + '),
            ]" :answer="number_format($calculation['subtotal'])" highlight />
        </div>
    </x-calc-step>

    <x-calc-step number="2" title="しょうひぜい を けいさんする" color="bg-pop-purple">
        <p class="mb-4 rounded-2xl bg-paper-deep px-4 py-3 text-sm font-bold">
            しょうひぜいは「ねだんの なんパーセントか」で きまるよ。<br>
            10% は <span class="font-black">100えんに つき 10えん</span>、8% は <span class="font-black">100えんに つき 8えん</span>。
        </p>

        <ul class="space-y-4">
            @foreach ($calculation['tax_groups'] as $group)
                <li>
                    <p class="mb-1 text-sm font-black text-ink/70">
                        {{ $group['rate'] }}% の しょうひん
                        @isset($taxLabels[$group['rate']])
                            <span class="font-bold text-ink/50">— {{ $taxLabels[$group['rate']] }}</span>
                        @endisset
                    </p>
                    <x-calc-formula :parts="[
                        number_format($group['subtotal']),
                        '×',
                        $group['rate'],
                        '÷',
                        '100',
                    ]" :answer="number_format($group['tax'])" />
                </li>
            @endforeach
        </ul>

        <p class="mt-4 text-xs font-bold text-ink/60">
            ※ 1えん より 小さい はんぱは きりすて（すてる）ルールに しているよ。
        </p>

        @if (count($calculation['tax_groups']) > 1)
            <div class="mt-5 border-t-4 border-dashed border-ink/20 pt-4">
                <p class="mb-1 text-sm font-black text-ink/70">しょうひぜい ぜんぶ</p>
                <x-calc-formula :parts="[
                    collect($calculation['tax_groups'])->map(fn ($group) => number_format($group['tax']))->implode(' + '),
                ]" :answer="number_format($calculation['tax_total'])" highlight />
            </div>
        @endif
    </x-calc-step>

    <x-calc-step number="3" title="ぜんぶ たして おかいけい！" color="bg-pop-red">
        <p class="mb-1 text-sm font-black text-ink/70">ぜいぬきの ねだん ＋ しょうひぜい</p>
        <x-calc-formula :parts="[
            number_format($calculation['subtotal']),
            '+',
            number_format($calculation['tax_total']),
        ]" :answer="number_format($calculation['total'])" highlight />
    </x-calc-step>
</div>
