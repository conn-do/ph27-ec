import { cn } from '@/lib/utils';

export default function StockStatus({ stock }: { stock: number }) {
    return (
        <span
            className={cn(
                'text-sm font-medium',
                stock > 0
                    ? 'text-emerald-700 dark:text-emerald-400'
                    : 'text-muted-foreground',
            )}
        >
            {stock > 0 ? '在庫あり' : '在庫切れ'}
        </span>
    );
}
