<?php

namespace App\Actions\Shop;

/**
 * おつりを「何が何まい」にくずして見せる。
 *
 * 大きいお金からじゅんばんに使う（りょうがえの考えかた）。
 */
class ChangeCalculator
{
    /**
     * @return list<array{value: int, label: string, type: string, count: int, amount: int}>
     */
    public function breakdown(int $change): array
    {
        $remaining = max(0, $change);
        $result = [];

        /** @var list<array{value: int, label: string, type: string}> $denominations */
        $denominations = config('shop.denominations');

        foreach ($denominations as $denomination) {
            $count = intdiv($remaining, $denomination['value']);

            if ($count === 0) {
                continue;
            }

            $remaining -= $count * $denomination['value'];

            $result[] = [
                ...$denomination,
                'count' => $count,
                'amount' => $count * $denomination['value'],
            ];
        }

        return $result;
    }

    /**
     * ちょうどぴったり払ったか。
     */
    public function isExact(int $change): bool
    {
        return $change === 0;
    }
}
