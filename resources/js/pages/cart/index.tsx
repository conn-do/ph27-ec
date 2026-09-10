import type { Page } from '@inertiajs/core';
import { Form, Head, Link } from '@inertiajs/react';
import { useState } from 'react';
import CartQuantityControl from '@/components/cart/cart-quantity-control';
import ProductImage from '@/components/products/product-image';
import { Button } from '@/components/ui/button';
import { categoryLabel } from '@/lib/category-label';
import { formatYen } from '@/lib/currency';
import { shop } from '@/routes';
import { destroy } from '@/routes/cart';
import { index as checkoutIndex } from '@/routes/checkout';
import type { Cart } from '@/types/product';

export default function CartIndex({ cart }: { cart: Cart }) {
    const [displayCart, setDisplayCart] = useState(cart);

    const syncCart = (page: Page) => {
        setDisplayCart(page.props.cart as unknown as Cart);
    };

    return (
        <>
            <Head title="カート" />
            <div className="flex flex-col gap-10">
                <div className="flex flex-wrap items-end justify-between gap-3 border-b border-[#ded5c6] pb-8">
                    <div className="space-y-2">
                        <p className="brand-kicker">Your selection</p>
                        <h1 className="editorial-title text-4xl font-bold sm:text-5xl">
                            カート
                        </h1>
                        <p className="text-sm text-muted-foreground">
                            {displayCart.quantity > 0
                                ? `${displayCart.quantity}点の商品`
                                : 'カートは空です'}
                        </p>
                    </div>
                    <Link
                        href={shop()}
                        className="text-sm underline underline-offset-4"
                    >
                        商品一覧へ戻る
                    </Link>
                </div>
                {displayCart.items.length > 0 ? (
                    <div className="grid gap-6 lg:grid-cols-[minmax(0,1fr)_20rem]">
                        <div className="divide-y rounded-[1.5rem] border border-[#ded5c6] bg-[#fffdfa]/75">
                            {displayCart.items.map((item) => (
                                <article
                                    key={item.product.id}
                                    className="grid min-w-0 gap-4 p-4 sm:grid-cols-[8rem_minmax(0,1fr)]"
                                >
                                    <ProductImage
                                        src={item.product.image}
                                        alt={item.product.name}
                                        className="rounded-md border"
                                    />
                                    <div className="flex min-w-0 flex-col gap-4">
                                        <div className="min-w-0">
                                            <p className="text-sm text-muted-foreground">
                                                {categoryLabel(item.product.category)}
                                            </p>
                                            <h2 className="mt-1 font-semibold wrap-anywhere">
                                                {item.product.name}
                                            </h2>
                                            <p className="mt-2 text-sm tabular-nums">
                                                {formatYen(item.product.price)}{' '}
                                                / 点
                                            </p>
                                        </div>
                                        <div className="flex flex-wrap items-end justify-between gap-4">
                                            <CartQuantityControl
                                                key={`${item.product.id}-${item.quantity}`}
                                                product={item.product}
                                                quantity={item.quantity}
                                                onSuccess={syncCart}
                                            />
                                            <Form
                                                {...destroy.form({
                                                    product: item.product.slug,
                                                })}
                                                onSuccess={syncCart}
                                                options={{
                                                    preserveScroll: true,
                                                }}
                                            >
                                                {({ processing }) => (
                                                    <Button
                                                        type="submit"
                                                        size="sm"
                                                        variant="ghost"
                                                        disabled={processing}
                                                    >
                                                        削除
                                                    </Button>
                                                )}
                                            </Form>
                                        </div>
                                        <p className="text-right font-medium tabular-nums">
                                            小計 {formatYen(item.subtotal)}
                                        </p>
                                    </div>
                                </article>
                            ))}
                        </div>
                        <aside className="brand-panel h-fit p-6">
                            <h2 className="editorial-title text-2xl font-bold">注文内容</h2>
                            <dl className="mt-5 space-y-3 text-sm">
                                <div className="flex justify-between gap-4">
                                    <dt className="text-muted-foreground">
                                        商品点数
                                    </dt>
                                    <dd className="tabular-nums">
                                        {displayCart.quantity}点
                                    </dd>
                                </div>
                                <div className="flex justify-between gap-4 border-t pt-3 text-base font-semibold">
                                    <dt>合計</dt>
                                    <dd className="tabular-nums">
                                        {formatYen(displayCart.total)}
                                    </dd>
                                </div>
                            </dl>
                            <Button asChild className="mt-5 w-full">
                                <Link href={checkoutIndex()}>購入手続きへ</Link>
                            </Button>
                        </aside>
                    </div>
                ) : (
                    <div className="brand-panel border-dashed px-4 py-16 text-center">
                        <h2 className="font-medium">カートは空です</h2>
                        <p className="mt-2 text-sm text-muted-foreground">
                            気になる商品をカートに追加してください。
                        </p>
                        <Button asChild className="mt-5">
                            <Link href={shop()}>商品を見る</Link>
                        </Button>
                    </div>
                )}
            </div>
        </>
    );
}
