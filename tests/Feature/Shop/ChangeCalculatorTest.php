<?php

use App\Actions\Shop\ChangeCalculator;

/**
 * おつりを おかねの まいすうに くずす けいさん。
 */
function changeBreakdown(int $change): array
{
    return (new ChangeCalculator)->breakdown($change);
}

test('returns nothing when the payment was exact', function () {
    expect(changeBreakdown(0))->toBe([]);
});

test('uses the biggest money first', function () {
    $breakdown = changeBreakdown(780);

    $summary = array_map(
        fn (array $money) => [$money['value'], $money['count']],
        $breakdown,
    );

    // 500×1 + 100×2 + 50×1 + 10×3 = 780
    expect($summary)->toBe([
        [500, 1],
        [100, 2],
        [50, 1],
        [10, 3],
    ]);
});

test('skips denominations that are not used', function () {
    $values = array_column(changeBreakdown(1000), 'value');

    expect($values)->toBe([1000]);
});

test('handles amounts that need 1 and 5 yen coins', function () {
    $summary = array_map(
        fn (array $money) => [$money['value'], $money['count']],
        changeBreakdown(9),
    );

    expect($summary)->toBe([
        [5, 1],
        [1, 4],
    ]);
});

test('the amounts always add back up to the change', function (int $change) {
    $total = array_sum(array_column(changeBreakdown($change), 'amount'));

    expect($total)->toBe($change);
})->with([1, 7, 63, 484, 999, 1234, 9999]);

test('treats a negative change as nothing', function () {
    expect(changeBreakdown(-100))->toBe([]);
});
