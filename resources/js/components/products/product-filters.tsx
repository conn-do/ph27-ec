import { Form, Link, usePage } from '@inertiajs/react';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { categoryLabel } from '@/lib/category-label';
import { shop } from '@/routes';
import type {
    ProductCategory,
    ProductFilters as Filters,
} from '@/types/product';

type Props = { categories: ProductCategory[]; filters: Filters };

const selectClassName =
    'h-10 w-full min-w-0 rounded-md border border-input bg-background px-3 text-base outline-none focus-visible:ring-2 focus-visible:ring-ring sm:text-sm';

export default function ProductFilters({ categories, filters }: Props) {
    const { errors } = usePage().props;

    return (
        <Form
            {...shop.form()}
            key={JSON.stringify(filters)}
            options={{ preserveState: false, preserveScroll: true }}
            className="grid gap-4 rounded-[1.5rem] border border-[#ded5c6] bg-[#fffdfa]/80 p-5 shadow-[0_16px_35px_-30px_rgba(16,24,39,0.5)] sm:grid-cols-2 lg:grid-cols-[2fr_1fr_1fr_auto]"
            role="search"
            aria-label="商品を絞り込む"
        >
            {({ processing }) => (
                <>
                    <div className="grid content-start gap-2">
                        <Label htmlFor="product-search">キーワード</Label>
                        <Input
                            id="product-search"
                            type="search"
                            name="q"
                            defaultValue={filters.q}
                            placeholder="商品名・説明から検索"
                            maxLength={100}
                            className="h-10"
                            aria-invalid={Boolean(errors.q)}
                            aria-describedby={
                                errors.q ? 'search-error' : undefined
                            }
                        />
                        <InputError id="search-error" message={errors.q} />
                    </div>
                    <div className="grid content-start gap-2">
                        <Label htmlFor="product-category">カテゴリ</Label>
                        <select
                            id="product-category"
                            name="category"
                            defaultValue={filters.category}
                            className={selectClassName}
                            aria-invalid={Boolean(errors.category)}
                            aria-describedby={
                                errors.category ? 'category-error' : undefined
                            }
                        >
                            <option value="">すべて</option>
                            {categories.map((category) => (
                                <option key={category.id} value={category.slug}>
                                    {categoryLabel(category)}
                                </option>
                            ))}
                        </select>
                        <InputError
                            id="category-error"
                            message={errors.category}
                        />
                    </div>
                    <div className="grid content-start gap-2">
                        <Label htmlFor="product-sort">並び順</Label>
                        <select
                            id="product-sort"
                            name="sort"
                            defaultValue={filters.sort}
                            className={selectClassName}
                            aria-invalid={Boolean(errors.sort)}
                            aria-describedby={
                                errors.sort ? 'sort-error' : undefined
                            }
                        >
                            <option value="newest">新着順</option>
                            <option value="price_asc">価格の安い順</option>
                            <option value="price_desc">価格の高い順</option>
                        </select>
                        <InputError id="sort-error" message={errors.sort} />
                    </div>
                    <div className="flex flex-wrap items-end gap-2">
                        <Button
                            type="submit"
                            disabled={processing}
                            className="h-10"
                        >
                            {processing ? '検索中…' : '条件を適用'}
                        </Button>
                        <Button variant="outline" asChild className="h-10">
                            <Link href={shop()} preserveState={false}>
                                クリア
                            </Link>
                        </Button>
                    </div>
                    <InputError
                        message={errors.page}
                        className="sm:col-span-2 lg:col-span-4"
                    />
                </>
            )}
        </Form>
    );
}
