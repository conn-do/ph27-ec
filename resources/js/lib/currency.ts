const yenNumberFormat = new Intl.NumberFormat('ja-JP', {
    maximumFractionDigits: 0,
});

export function formatYen(price: number): string {
    return `¥${yenNumberFormat.format(price)}`;
}
