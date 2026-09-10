import { Head, Link } from '@inertiajs/react';
import ProductCard from '@/components/products/product-card';
import ProductImage from '@/components/products/product-image';
import { categoryLabel } from '@/lib/category-label';
import { shop } from '@/routes';
import type { Product, ProductCategory } from '@/types/product';

type Props = {
    newProducts: Product[];
    featuredProducts: Product[];
    categories: (ProductCategory & { description: string | null })[];
};

const categoryImages: Record<string, string> = {
    writing: '/images/products/minimal-mechanical-pencil.jpg',
    notebook: '/images/products/grid-note-a5.jpg',
    desk: '/images/products/concrete-pen-stand.jpg',
    storage: '/images/products/canvas-tool-pouch.jpg',
    tools: '/images/products/brass-clip-set.jpg',
};

export default function Welcome({
    newProducts,
    featuredProducts,
    categories,
}: Props) {
    return (
        <>
            <Head title="すごい文房具" />
            <div className="flex flex-col gap-24 sm:gap-32">
                <section className="grid gap-5 lg:grid-cols-12 lg:gap-7">
                    <div className="brand-panel relative overflow-hidden bg-[#101827] p-8 text-[#fffaf1] sm:p-12 lg:col-span-7 lg:min-h-[36rem] lg:p-16">
                        <div className="relative z-10 flex h-full max-w-xl flex-col items-start justify-between gap-16">
                            <div className="space-y-6">
                                <p className="text-[0.68rem] font-semibold tracking-[0.26em] text-[#d2a75f] uppercase">
                                    SUGOI STATIONERY / TOKYO
                                </p>
                                <h1 className="editorial-title max-w-lg text-5xl leading-[1.14] font-bold text-[#fffaf1] sm:text-6xl lg:text-7xl">
                                    書くことが、
                                    <br />
                                    好きになる。
                                </h1>
                                <p className="max-w-md text-sm leading-7 text-[#d9d2c5] sm:text-base">
                                    使うたび、目にするたび、少し心が整う。
                                    <br />
                                    毎日に寄り添う文房具を集めました。
                                </p>
                            </div>
                            <Link href={shop()} className="rounded-full bg-[#d2a75f] px-6 py-3 text-sm font-bold text-[#101827] transition hover:bg-[#fffaf1]">
                                文房具を選ぶ
                            </Link>
                        </div>
                        <div className="absolute right-[-5rem] bottom-[-4rem] h-72 w-72 rounded-full border border-[#d2a75f]/45 sm:h-96 sm:w-96" />
                        <div className="absolute right-8 bottom-8 h-24 w-24 rounded-full bg-[#f4dfe0] blur-2xl" />
                    </div>
                    <div className="grid gap-5 sm:grid-cols-2 lg:col-span-5 lg:grid-cols-1">
                        <div className="brand-panel group relative min-h-72 overflow-hidden sm:min-h-96 lg:min-h-0">
                            <img
                                src="/images/products/minimal-mechanical-pencil.jpg"
                                alt="黒と金のHB鉛筆"
                                className="absolute inset-0 size-full object-cover transition duration-700 group-hover:scale-105"
                            />
                            <div className="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#101827]/80 to-transparent p-6 text-[#fffaf1]">
                                <p className="text-xs font-semibold tracking-[0.18em] text-[#d2a75f] uppercase">Signature</p>
                                <p className="editorial-title mt-2 text-2xl font-bold text-white">静かな一本から。</p>
                            </div>
                        </div>
                        <div className="brand-panel grid min-h-52 grid-cols-[1fr_1.1fr] overflow-hidden bg-[#dfeef3]">
                            <div className="p-6 sm:p-8">
                                <p className="brand-kicker">New color</p>
                                <p className="editorial-title mt-3 text-2xl font-bold">淡いブルーの余白。</p>
                                <Link href={shop({ query: { category: 'notebook' } })} className="mt-5 inline-block text-sm font-semibold underline underline-offset-4">
                                    ノートを見る
                                </Link>
                            </div>
                            <img src="/images/products/grid-note-a5.jpg" alt="水色のノート" className="size-full object-cover" />
                        </div>
                    </div>
                </section>

                <section className="space-y-9">
                    <div className="flex flex-wrap items-end justify-between gap-5">
                        <div>
                            <p className="brand-kicker">New arrivals</p>
                            <h2 className="editorial-title mt-3 text-4xl font-bold sm:text-5xl">新着商品</h2>
                        </div>
                        <Link href={shop()} className="brand-outline-link">すべての商品を見る</Link>
                    </div>
                    <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                        {newProducts.map((product) => (
                            <ProductCard key={product.id} product={product} />
                        ))}
                    </div>
                </section>

                <section className="space-y-9">
                    <div>
                        <p className="brand-kicker">By category</p>
                        <h2 className="editorial-title mt-3 text-4xl font-bold sm:text-5xl">気分から、選ぶ。</h2>
                    </div>
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                        {categories.map((category) => (
                            <Link
                                key={category.id}
                                href={shop({ query: { category: category.slug } })}
                                className="group brand-panel overflow-hidden p-3 transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_50px_-28px_rgba(16,24,39,0.55)]"
                            >
                                <ProductImage
                                    src={categoryImages[category.slug] ?? null}
                                    alt={categoryLabel(category)}
                                    className="rounded-[1rem]"
                                />
                                <div className="p-3 pb-2">
                                    <p className="editorial-title text-xl font-bold">{categoryLabel(category)}</p>
                                    <p className="mt-2 line-clamp-2 text-xs leading-5 text-muted-foreground">{category.description}</p>
                                </div>
                            </Link>
                        ))}
                    </div>
                </section>

                <section className="grid gap-7 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
                    <div className="brand-panel overflow-hidden bg-[#f2e1e2] p-5 sm:p-8">
                        <img src="/images/products/soft-ink-gel-pen.jpg" alt="淡いピンクのペン" className="aspect-[4/3] w-full rounded-[1.2rem] object-cover" />
                    </div>
                    <div className="space-y-7 lg:pl-10">
                        <p className="brand-kicker">Featured collection</p>
                        <h2 className="editorial-title text-4xl leading-tight font-bold sm:text-5xl">手元に、
                            <br />小さなときめきを。</h2>
                        <p className="max-w-xl text-sm leading-8 text-muted-foreground sm:text-base">
                            素材の手ざわり、色の静けさ、使い始めた日の気分。
                            何気ない道具を、長く手元に置きたくなるものだけ選びました。
                        </p>
                        <Link href={shop({ query: { sort: 'price_desc' } })} className="brand-outline-link">特集を見る</Link>
                    </div>
                </section>

                <section className="grid gap-8 border-y border-[#ded5c6] py-12 lg:grid-cols-[1fr_1.25fr] lg:py-20">
                    <div>
                        <p className="brand-kicker">Our philosophy</p>
                        <h2 className="editorial-title mt-4 text-4xl font-bold sm:text-5xl">余白まで、
                            <br />美しい道具を。</h2>
                    </div>
                    <div className="space-y-6 text-base leading-8 text-[#495363]">
                        <p>すごい文房具は、書く・しまう・整える時間を少しだけ豊かにする、文房具のセレクトショップです。</p>
                        <p>手に取った瞬間の心地よさを大切に、暮らしの中で静かに輝くものをお届けします。</p>
                    </div>
                </section>

                <section className="space-y-9">
                    <div className="flex flex-wrap items-end justify-between gap-5">
                        <div>
                            <p className="brand-kicker">Editor's selection</p>
                            <h2 className="editorial-title mt-3 text-4xl font-bold sm:text-5xl">おすすめ</h2>
                        </div>
                        <Link href={shop()} className="text-sm font-semibold underline underline-offset-4">商品一覧へ</Link>
                    </div>
                    <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                        {featuredProducts.map((product) => (
                            <ProductCard key={product.id} product={product} />
                        ))}
                    </div>
                </section>
            </div>
        </>
    );
}
