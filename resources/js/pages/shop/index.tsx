import { Head, Link } from '@inertiajs/react';
import ProductCard from '@/components/products/product-card';
import ProductFilters from '@/components/products/product-filters';
import { Button } from '@/components/ui/button';
import type {
    PaginatedProducts,
    ProductCategory,
    ProductFilters as Filters,
} from '@/types/product';

type Props = {
    products: PaginatedProducts;
    categories: ProductCategory[];
    filters: Filters;
};

export default function Shop({ products, categories, filters }: Props) {
    return (
        <>
            <Head title="商品一覧" />
            <div className="flex flex-col gap-10">
                <div className="grid gap-5 border-b border-[#ded5c6] pb-9 sm:grid-cols-[1fr_auto] sm:items-end">
                    <div className="space-y-3">
                        <p className="brand-kicker">The collection</p>
                        <h1 className="editorial-title text-5xl font-bold sm:text-6xl">商品一覧</h1>
                    </div>
                    <p className="max-w-xs text-sm leading-6 text-muted-foreground">
                        毎日の筆記時間を、少し特別にするものを集めました。
                    </p>
                </div>
                <ProductFilters categories={categories} filters={filters} />
                <p role="status" className="text-sm text-muted-foreground">
                    {products.total > 0
                        ? `${products.total}件中 ${products.from}〜${products.to}件を表示`
                        : '0件の商品'}
                </p>
                {products.data.length > 0 ? (
                    <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        {products.data.map((product) => (
                            <ProductCard key={product.id} product={product} />
                        ))}
                    </div>
                ) : (
                    <div className="rounded-lg border border-dashed px-4 py-16 text-center">
                        <h2 className="font-medium">
                            商品が見つかりませんでした
                        </h2>
                        <p className="mt-2 text-sm text-muted-foreground">
                            キーワードやカテゴリを変更してお試しください。
                        </p>
                    </div>
                )}
                {products.last_page > 1 && (
                    <nav
                        aria-label="商品一覧のページ切り替え"
                        className="flex flex-wrap items-center justify-center gap-4"
                    >
                        {products.prev_page_url ? (
                            <Button variant="outline" asChild>
                                <Link href={products.prev_page_url}>前へ</Link>
                            </Button>
                        ) : (
                            <Button variant="outline" disabled>
                                前へ
                            </Button>
                        )}
                        <span className="text-sm tabular-nums">
                            {products.current_page} / {products.last_page}
                        </span>
                        {products.next_page_url ? (
                            <Button variant="outline" asChild>
                                <Link href={products.next_page_url}>次へ</Link>
                            </Button>
                        ) : (
                            <Button variant="outline" disabled>
                                次へ
                            </Button>
                        )}
                    </nav>
                )}
            </div>
        </>
    );
}
