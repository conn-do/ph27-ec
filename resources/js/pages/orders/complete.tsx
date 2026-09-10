import { Head, Link } from '@inertiajs/react';
import type { ReactNode } from 'react';
import { index as cartIndex } from '@/routes/cart';
import { index as productsIndex } from '@/routes/products';

type Order = {
    id: number;
    total_price: number;
};

export default function OrderComplete({ order }: { order: Order }) {
    return (
        <>
            <Head title="注文完了" />

            <main className="min-h-screen bg-white text-[#102034]">
                <div className="mx-auto flex min-h-screen max-w-5xl flex-col px-8 py-8">
                    <header className="flex flex-col gap-4">
                        <Link href={productsIndex.url()} className="w-fit">
                            <img
                                src="/images/ec-logo.png"
                                alt="すごい文房具ECサイト"
                                className="h-16 w-auto object-contain"
                            />
                        </Link>
                        <Link
                            href={cartIndex.url()}
                            className="w-fit text-2xl text-[#1f6f96] underline underline-offset-4"
                        >
                            カートを見る
                        </Link>
                    </header>

                    <section className="flex flex-1 flex-col justify-center gap-10 py-12">
                        <div className="space-y-8">
                            <h1 className="text-3xl font-semibold tracking-normal">
                                注文が完了しました！
                            </h1>

                            <div className="space-y-4 text-2xl">
                                <p>注文ID: {order.id}</p>
                                <p>
                                    合計金額: ¥
                                    {order.total_price.toLocaleString()}
                                </p>
                            </div>
                        </div>
                    </section>

                    <footer className="pb-8 text-3xl text-[#333]">
                        © HAL東京
                    </footer>
                </div>
            </main>
        </>
    );
}

OrderComplete.layout = (page: ReactNode) => page;
