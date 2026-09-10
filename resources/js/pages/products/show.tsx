import { Head, Link } from '@inertiajs/react';
import AddToCartButton from '@/components/cart/add-to-cart-button';
import ProductImage from '@/components/products/product-image';
import StockStatus from '@/components/products/stock-status';
import { categoryLabel } from '@/lib/category-label';
import { formatYen } from '@/lib/currency';
import { shop } from '@/routes';
import type { Product } from '@/types/product';

export default function ProductShow({ product }: { product: Product }) {
    return (
        <>
            <Head title={product.name} />
            <div className="flex flex-col gap-10">
                <Link
                    href={shop()}
                    className="w-fit text-sm underline underline-offset-4"
                >
                    商品一覧に戻る
                </Link>
                <article className="grid min-w-0 gap-8 md:grid-cols-2 md:gap-14">
                    <ProductImage
                        src={product.image}
                        alt={product.name}
                        loading="eager"
                        className="rounded-[1.75rem] border border-[#ded5c6] shadow-[0_22px_45px_-30px_rgba(16,24,39,0.55)]"
                    />
                    <div className="flex min-w-0 flex-col gap-6">
                        <div className="space-y-3">
                            <Link
                                href={shop({
                                    query: { category: product.category.slug },
                                })}
                                className="text-sm text-muted-foreground underline-offset-4 hover:underline"
                            >
                                {categoryLabel(product.category)}
                            </Link>
                            <h1 className="editorial-title text-4xl leading-tight font-bold wrap-anywhere sm:text-5xl">
                                {product.name}
                            </h1>
                        </div>
                        <p className="text-2xl font-semibold tabular-nums">
                            {formatYen(product.price)}
                        </p>
                        <AddToCartButton product={product} />
                        <dl className="grid grid-cols-[auto_1fr] gap-x-6 gap-y-3 rounded-[1.25rem] border border-[#ded5c6] bg-[#fffdfa]/70 p-5 text-sm">
                            <dt className="text-muted-foreground">在庫状態</dt>
                            <dd>
                                <StockStatus stock={product.stock} />
                            </dd>
                            <dt className="text-muted-foreground">在庫数</dt>
                            <dd className="tabular-nums">{product.stock}点</dd>
                        </dl>
                        <section
                            aria-labelledby="product-description"
                            className="space-y-3"
                        >
                            <h2
                                id="product-description"
                                className="font-semibold"
                            >
                                商品について
                            </h2>
                            <p className="text-sm leading-7 wrap-anywhere whitespace-pre-line text-muted-foreground">
                                {product.description}
                            </p>
                        </section>
                    </div>
                </article>
            </div>
        </>
    );
}
