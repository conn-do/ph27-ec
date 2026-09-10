import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { formatYen } from '@/lib/currency';
import { shop } from '@/routes';
import { complete } from '@/routes/checkout';
import type { OrderHistory } from '@/types/product';

const statusLabel = (status: string): string => {
    return status === 'pending' ? '受付済み' : status;
};

export default function OrdersIndex({ orders }: { orders: OrderHistory[] }) {
    return (
        <>
            <Head title="注文履歴" />
            <div className="flex flex-col gap-10">
                <div className="space-y-2">
                    <p className="brand-kicker">Your orders</p>
                    <h1 className="editorial-title text-4xl font-bold sm:text-5xl">
                        注文履歴
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        これまでのご注文を確認できます。
                    </p>
                </div>
                {orders.length > 0 ? (
                    <div className="overflow-hidden rounded-[1.5rem] border border-[#ded5c6] bg-[#fffdfa]/75 shadow-[0_18px_45px_-32px_rgba(16,24,39,0.5)]">
                        <div className="hidden grid-cols-[minmax(7rem,1fr)_minmax(8rem,1fr)_minmax(5rem,1fr)_minmax(5rem,1fr)_minmax(7rem,1fr)_auto] gap-4 border-b bg-muted/40 px-4 py-3 text-sm text-muted-foreground md:grid">
                            <span>注文番号</span>
                            <span>注文日</span>
                            <span>ステータス</span>
                            <span>商品点数</span>
                            <span className="text-right">合計金額</span>
                            <span aria-hidden="true" />
                        </div>
                        <ul className="divide-y">
                            {orders.map((order) => (
                                <li
                                    key={order.id}
                                    className="grid gap-3 p-4 md:grid-cols-[minmax(7rem,1fr)_minmax(8rem,1fr)_minmax(5rem,1fr)_minmax(5rem,1fr)_minmax(7rem,1fr)_auto] md:items-center md:gap-4"
                                >
                                    <div>
                                        <span className="text-sm text-muted-foreground md:hidden">
                                            注文番号：
                                        </span>
                                        <span className="font-medium">#{order.id}</span>
                                    </div>
                                    <div className="text-sm">
                                        <span className="text-muted-foreground md:hidden">
                                            注文日：
                                        </span>
                                        {new Intl.DateTimeFormat('ja-JP', {
                                            dateStyle: 'medium',
                                        }).format(new Date(order.created_at))}
                                    </div>
                                    <div className="text-sm">
                                        <span className="text-muted-foreground md:hidden">
                                            ステータス：
                                        </span>
                                        {statusLabel(order.status)}
                                    </div>
                                    <div className="text-sm">
                                        <span className="text-muted-foreground md:hidden">
                                            商品点数：
                                        </span>
                                        {order.items_count}点
                                    </div>
                                    <div className="text-sm font-medium tabular-nums md:text-right">
                                        <span className="font-normal text-muted-foreground md:hidden">
                                            合計金額：
                                        </span>
                                        {formatYen(order.total_price)}
                                    </div>
                                    <Button size="sm" variant="outline" asChild>
                                        <Link href={complete({ order: order.id })}>
                                            詳細を見る
                                        </Link>
                                    </Button>
                                </li>
                            ))}
                        </ul>
                    </div>
                ) : (
                    <div className="brand-panel border-dashed px-4 py-16 text-center">
                        <h2 className="font-medium">注文履歴はありません</h2>
                        <p className="mt-2 text-sm text-muted-foreground">
                            商品を購入すると、ここに注文履歴が表示されます。
                        </p>
                        <Button asChild className="mt-5">
                            <Link href={shop()}>商品一覧を見る</Link>
                        </Button>
                    </div>
                )}
            </div>
        </>
    );
}
