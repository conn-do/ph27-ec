import type { Page } from '@inertiajs/core';
import { Form } from '@inertiajs/react';
import { useEffect, useRef, useState } from 'react';
import { Button } from '@/components/ui/button';
import { store } from '@/routes/cart';
import type { Product } from '@/types/product';

export default function AddToCartButton({ product }: { product: Product }) {
    const outOfStock = product.stock === 0;
    const [added, setAdded] = useState(false);
    const locked = useRef(false);
    const resetTimer = useRef<ReturnType<typeof setTimeout>>(undefined);

    useEffect(() => {
        return () => clearTimeout(resetTimer.current);
    }, []);

    const handleSuccess = (page: Page) => {
        const flash = page.props.flash as { error: string | null };

        if (flash.error) {
            locked.current = false;

            return;
        }

        setAdded(true);
        resetTimer.current = setTimeout(() => {
            setAdded(false);
            locked.current = false;
        }, 1200);
    };

    return (
        <Form
            {...store.form({ product: product.slug })}
            onBefore={() => {
                if (locked.current) {
                    return false;
                }

                locked.current = true;

                return true;
            }}
            onSuccess={handleSuccess}
            onError={() => {
                locked.current = false;
            }}
            options={{ preserveScroll: true }}
        >
            {({ processing }) => (
                <Button
                    type="submit"
                    disabled={outOfStock || processing || added}
                    className="h-11 w-full rounded-full bg-[#101827] text-[#fffaf1] hover:bg-[#a87632]"
                >
                    {outOfStock ? '在庫切れ' : added ? '✓ カートに追加しました' : processing ? '追加中…' : 'カートに追加'}
                </Button>
            )}
        </Form>
    );
}
