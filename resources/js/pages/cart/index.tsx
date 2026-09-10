import { Form, Head, Link } from '@inertiajs/react';
import type { ReactNode } from 'react';
import { store as orderStore } from '@/routes/orders';
import { index as productsIndex } from '@/routes/products';

type CartItem = {
    key: string;
    name: string;
    price: number;
    image: string;
    quantity: number;
    subtotal: number;
};

export default function CartIndex({
    items,
    total,
}: {
    items: CartItem[];
    total: number;
}) {
    return (
        <>
            <Head title="カート" />

            <main className="min-h-screen bg-[#f7f3ec] text-[#102034]">
                <header className="border-b border-[#d8c7a2] bg-white/90">
                    <div className="mx-auto flex max-w-5xl flex-col gap-4 px-5 py-6 sm:flex-row sm:items-center sm:justify-between">
                        <Link href={productsIndex.url()}>
                            <img
                                src="/images/ec-logo.png"
                                alt="すごい文房具ECサイト"
                                className="h-16 w-auto object-contain"
                            />
                        </Link>
                        <Link
                            href={productsIndex.url()}
                            className="w-fit text-lg font-semibold text-[#1f6f96] underline underline-offset-4"
                        >
                            商品一覧へ戻る
                        </Link>
                    </div>
                </header>

                <section className="mx-auto flex max-w-5xl flex-col gap-6 px-5 py-10">
                    <div>
                        <p className="text-sm font-semibold text-[#b79652] uppercase">
                            Shopping Cart
                        </p>
                        <h1 className="text-4xl font-bold tracking-normal">
                            カート
                        </h1>
                    </div>

                    {items.length === 0 ? (
                        <div className="border border-dashed border-[#d8c7a2] bg-white p-10 text-center">
                            <p className="text-lg font-semibold">
                                カートは空です。
                            </p>
                            <Link
                                href={productsIndex.url()}
                                className="mt-4 inline-flex bg-[#102034] px-5 py-3 text-sm font-semibold text-white"
                            >
                                商品を見る
                            </Link>
                        </div>
                    ) : (
                        <>
                            <div className="divide-y divide-[#d8c7a2] border border-[#d8c7a2] bg-white">
                                {items.map((item) => (
                                    <div
                                        key={item.key}
                                        className="grid gap-4 p-4 sm:grid-cols-[7rem_1fr_auto] sm:items-center"
                                    >
                                        <img
                                            src={item.image}
                                            alt={item.name}
                                            className="aspect-square w-28 object-cover"
                                        />
                                        <div className="space-y-1">
                                            <h2 className="text-xl font-bold">
                                                {item.name}
                                            </h2>
                                            <p className="text-sm text-[#536071]">
                                                ¥{item.price.toLocaleString()} x{' '}
                                                {item.quantity}
                                            </p>
                                            <p className="text-lg font-bold">
                                                小計: ¥
                                                {item.subtotal.toLocaleString()}
                                            </p>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            <div className="flex flex-col gap-4 border border-[#d8c7a2] bg-white p-6 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p className="text-sm font-semibold text-[#536071]">
                                        合計金額
                                    </p>
                                    <p className="text-3xl font-bold">
                                        ¥{total.toLocaleString()}
                                    </p>
                                </div>
                                <Form {...orderStore.form()}>
                                    {({ processing }) => (
                                        <button
                                            type="submit"
                                            disabled={processing}
                                            className="bg-[#102034] px-7 py-3 text-sm font-semibold text-white transition hover:bg-[#203958] disabled:opacity-60"
                                        >
                                            注文を確定する
                                        </button>
                                    )}
                                </Form>
                            </div>
                        </>
                    )}
                </section>
            </main>
        </>
    );
}

CartIndex.layout = (page: ReactNode) => page;
