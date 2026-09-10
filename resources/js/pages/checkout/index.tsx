import { Form, Head, Link, usePage } from '@inertiajs/react';
import InputError from '@/components/input-error';
import ProductImage from '@/components/products/product-image';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { formatYen } from '@/lib/currency';
import { shop } from '@/routes';
import { store } from '@/routes/checkout';
import type { Cart } from '@/types/product';

type Props = {
    cart: Cart;
    checkoutToken: string;
};

export default function CheckoutIndex({ cart, checkoutToken }: Props) {
    const { errors } = usePage().props;

    return (
        <>
            <Head title="購入手続き" />
            <div className="flex flex-col gap-10">
                <div className="space-y-2">
                    <p className="brand-kicker">Checkout</p>
                    <h1 className="editorial-title text-4xl font-bold sm:text-5xl">
                        購入手続き
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        お届け先を入力して注文内容を確認してください。
                    </p>
                </div>
                <div className="grid gap-6 lg:grid-cols-[minmax(0,1fr)_22rem]">
                    <Form
                        {...store.form()}
                        className="brand-panel grid content-start gap-5 p-6"
                    >
                        {({ processing }) => (
                            <>
                                <input
                                    type="hidden"
                                    name="checkout_token"
                                    value={checkoutToken}
                                />
                                <div className="grid gap-2">
                                    <Label htmlFor="customer-name">お名前</Label>
                                    <Input
                                        id="customer-name"
                                        name="customer_name"
                                        autoComplete="name"
                                        aria-invalid={Boolean(errors.customer_name)}
                                    />
                                    <InputError message={errors.customer_name} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="postal-code">郵便番号</Label>
                                    <Input
                                        id="postal-code"
                                        name="postal_code"
                                        inputMode="numeric"
                                        autoComplete="postal-code"
                                        placeholder="123-4567"
                                        aria-invalid={Boolean(errors.postal_code)}
                                    />
                                    <InputError message={errors.postal_code} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="address">住所</Label>
                                    <textarea
                                        id="address"
                                        name="address"
                                        autoComplete="street-address"
                                        rows={4}
                                        className="w-full rounded-md border border-input bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                                        aria-invalid={Boolean(errors.address)}
                                    />
                                    <InputError message={errors.address} />
                                </div>
                                <Button type="submit" disabled={processing}>
                                    {processing ? '注文を確定中…' : '注文を確定する'}
                                </Button>
                            </>
                        )}
                    </Form>
                    <aside className="brand-panel h-fit p-6">
                        <h2 className="editorial-title text-2xl font-bold">注文内容</h2>
                        <ul className="mt-5 divide-y">
                            {cart.items.map((item) => (
                                <li
                                    key={item.product.id}
                                    className="flex gap-3 py-3 first:pt-0"
                                >
                                    <ProductImage
                                        src={item.product.image}
                                        alt={item.product.name}
                                        className="size-16 shrink-0 rounded-md border"
                                    />
                                    <div className="min-w-0 flex-1">
                                        <p className="font-medium wrap-anywhere">
                                            {item.product.name}
                                        </p>
                                        <p className="mt-1 text-sm text-muted-foreground">
                                            {formatYen(item.product.price)} ×{' '}
                                            {item.quantity}
                                        </p>
                                    </div>
                                    <p className="text-sm font-medium tabular-nums">
                                        {formatYen(item.subtotal)}
                                    </p>
                                </li>
                            ))}
                        </ul>
                        <dl className="mt-4 space-y-3 border-t pt-4 text-sm">
                            <div className="flex justify-between gap-4">
                                <dt className="text-muted-foreground">商品点数</dt>
                                <dd>{cart.quantity}点</dd>
                            </div>
                            <div className="flex justify-between gap-4 text-base font-semibold">
                                <dt>合計</dt>
                                <dd className="tabular-nums">
                                    {formatYen(cart.total)}
                                </dd>
                            </div>
                        </dl>
                    </aside>
                </div>
                <Link
                    href={shop()}
                    className="w-fit text-sm underline underline-offset-4"
                >
                    商品一覧へ戻る
                </Link>
            </div>
        </>
    );
}
