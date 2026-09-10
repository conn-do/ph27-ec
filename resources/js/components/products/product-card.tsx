import { Link } from '@inertiajs/react';
import AddToCartButton from '@/components/cart/add-to-cart-button';
import ProductImage from '@/components/products/product-image';
import StockStatus from '@/components/products/stock-status';
import { categoryLabel } from '@/lib/category-label';
import { formatYen } from '@/lib/currency';
import { show } from '@/routes/products';
import type { Product } from '@/types/product';

export default function ProductCard({ product }: { product: Product }) {
    return (
        <article className="group min-w-0 overflow-hidden rounded-[1.5rem] border border-[#ded5c6] bg-card text-card-foreground shadow-[0_16px_35px_-28px_rgba(16,24,39,0.6)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_45px_-26px_rgba(16,24,39,0.55)]">
            <Link
                href={show({ product: product.slug })}
                className="block outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-inset"
            >
                <div className="overflow-hidden bg-[#f0ebe2]">
                    <ProductImage
                        src={product.image}
                        alt={product.name}
                        className="transition duration-500 group-hover:scale-[1.035]"
                    />
                </div>
                <div className="flex flex-col gap-3 p-5 pb-4">
                    <p className="brand-kicker">
                        {categoryLabel(product.category)}
                    </p>
                    <h2 className="editorial-title min-h-12 text-xl leading-6 font-bold wrap-anywhere">
                        {product.name}
                    </h2>
                    <div className="flex flex-wrap items-center justify-between gap-2">
                        <p className="font-semibold tabular-nums">
                            {formatYen(product.price)}
                        </p>
                        <StockStatus stock={product.stock} />
                    </div>
                </div>
            </Link>
            <div className="p-5 pt-0">
                <AddToCartButton product={product} />
            </div>
        </article>
    );
}
