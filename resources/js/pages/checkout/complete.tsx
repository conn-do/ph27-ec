import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { formatYen } from '@/lib/currency';
import { shop } from '@/routes';
import type { Order } from '@/types/product';

export default function CheckoutComplete({ order }: { order: Order }) {
    return (
        <>
            <Head title="注文完了" />
            <div className="mx-auto flex max-w-2xl flex-col gap-7">
                <div className="space-y-2">
                    <p className="brand-kicker">Order complete</p>
                    <h1 className="editorial-title text-4xl font-bold sm:text-5xl">
                        ご注文ありがとうございます
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        ご注文を受け付けました。
                    </p>
                </div>
                <section className="brand-panel p-6">
                    <dl className="grid gap-4 text-sm sm:grid-cols-2">
                        <div>
                            <dt className="text-muted-foreground">注文番号</dt>
                            <dd className="mt-1 font-semibold">#{order.id}</dd>
                        </div>
                        <div>
                            <dt className="text-muted-foreground">合計金額</dt>
                            <dd className="mt-1 font-semibold tabular-nums">
                                {formatYen(order.total_price)}
                            </dd>
                        </div>
                    </dl>
                    <div className="mt-6 border-t pt-5">
                        <h2 className="font-semibold">注文商品</h2>
                        <ul className="mt-3 divide-y">
                            {order.items.map((item) => (
                                <li
                                    key={item.id}
                                    className="flex justify-between gap-4 py-3 text-sm"
                                >
                                    <div className="min-w-0">
                                        <p className="font-medium wrap-anywhere">
                                            {item.product_name}
                                        </p>
                                        <p className="mt-1 text-muted-foreground">
                                            {formatYen(item.price)} × {item.quantity}
                                        </p>
                                    </div>
                                    <p className="shrink-0 font-medium tabular-nums">
                                        {formatYen(item.price * item.quantity)}
                                    </p>
                                </li>
                            ))}
                        </ul>
                    </div>
                </section>
                <Button asChild className="w-fit">
                    <Link href={shop()}>商品一覧へ戻る</Link>
                </Button>
            </div>
        </>
    );
}
