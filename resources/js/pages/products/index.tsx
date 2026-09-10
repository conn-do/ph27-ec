import { Form, Head, Link } from '@inertiajs/react';
import { ShoppingCart } from 'lucide-react';
import type { ReactNode } from 'react';
import { store } from '@/routes/cart';
import { index as cartIndex } from '@/routes/cart';

type Product = {
    key: string;
    name: string;
    description: string;
    price: number;
    image: string;
    accent: string;
};

export default function ProductsIndex({
    products,
    cartCount,
}: {
    products: Product[];
    cartCount: number;
}) {
    return (
        <>
            <Head title="すごい文房具ECサイト" />

            <main className="min-h-screen bg-[#f7f3ec] text-[#102034]">
                <header className="border-b border-[#d8c7a2] bg-white/90">
                    <div className="mx-auto flex max-w-6xl flex-col gap-5 px-5 py-6 sm:flex-row sm:items-center sm:justify-between">
                        <Link href="/" className="block w-fit">
                            <img
                                src="/images/ec-logo.png"
                                alt="すごい文房具ECサイト"
                                className="h-16 w-auto object-contain"
                            />
                        </Link>

                        <Link
                            href={cartIndex.url()}
                            className="inline-flex w-fit items-center gap-2 border border-[#b79652] bg-[#102034] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#203958]"
                        >
                            <ShoppingCart className="size-4" />
                            カートを見る
                            {cartCount > 0 && (
                                <span className="bg-[#b79652] px-2 py-0.5 text-xs text-white">
                                    {cartCount}
                                </span>
                            )}
                        </Link>
                    </div>
                </header>

                <section className="mx-auto grid max-w-6xl gap-8 px-5 py-10 lg:grid-cols-[0.8fr_1.2fr] lg:items-center">
                    <div className="space-y-4">
                        <p className="text-sm font-semibold text-[#b79652] uppercase">
                            Lesson EC Store
                        </p>
                        <h1 className="text-4xl font-bold tracking-normal text-[#102034] sm:text-5xl">
                            授業で作った注文フローを、ちゃんと動くECサイトに。
                        </h1>
                        <p className="max-w-xl text-base leading-7 text-[#536071]">
                            商品を選んでカートに入れ、注文を完了するとデータベースに合計金額が保存されます。
                        </p>
                    </div>

                    <div className="overflow-hidden border border-[#d8c7a2] bg-white">
                        <img
                            src="/images/products/note.png"
                            alt="ライトブルーノート"
                            className="h-72 w-full object-cover"
                        />
                    </div>
                </section>

                <section className="mx-auto grid max-w-6xl grid-cols-1 gap-5 px-5 pb-12 md:grid-cols-3">
                    {products.map((product) => (
                        <article
                            key={product.key}
                            className="flex min-h-full flex-col overflow-hidden border border-[#d8c7a2] bg-white"
                        >
                            <img
                                src={product.image}
                                alt={product.name}
                                className="aspect-[4/3] w-full object-cover"
                            />

                            <div className="flex flex-1 flex-col gap-4 p-5">
                                <div className="flex items-start justify-between gap-3">
                                    <div>
                                        <p className="text-xs font-semibold text-[#b79652]">
                                            {product.accent}
                                        </p>
                                        <h2 className="text-xl font-bold">
                                            {product.name}
                                        </h2>
                                    </div>
                                    <p className="shrink-0 text-lg font-bold">
                                        ¥{product.price.toLocaleString()}
                                    </p>
                                </div>

                                <p className="flex-1 text-sm leading-6 text-[#536071]">
                                    {product.description}
                                </p>

                                <Form
                                    {...store.form()}
                                    className="flex items-center gap-3"
                                >
                                    {({ processing }) => (
                                        <>
                                            <input
                                                type="hidden"
                                                name="product_key"
                                                value={product.key}
                                            />
                                            <input
                                                type="number"
                                                name="quantity"
                                                defaultValue={1}
                                                min={1}
                                                max={20}
                                                className="h-10 w-20 border border-[#d8c7a2] px-3 text-sm outline-none focus:border-[#b79652]"
                                            />
                                            <button
                                                type="submit"
                                                disabled={processing}
                                                className="h-10 flex-1 bg-[#102034] px-4 text-sm font-semibold text-white transition hover:bg-[#203958] disabled:opacity-60"
                                            >
                                                カートに入れる
                                            </button>
                                        </>
                                    )}
                                </Form>
                            </div>
                        </article>
                    ))}
                </section>
            </main>
        </>
    );
}

ProductsIndex.layout = (page: ReactNode) => page;
