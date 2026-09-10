import type { Page } from '@inertiajs/core';
import { router } from '@inertiajs/react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { update } from '@/routes/cart';
import type { Cart, Product } from '@/types/product';

type Props = {
    product: Product;
    quantity: number;
    onSuccess: (page: Page) => void;
};

export default function CartQuantityControl({
    product,
    quantity,
    onSuccess,
}: Props) {
    const [value, setValue] = useState(String(quantity));
    const [processing, setProcessing] = useState(false);

    const saveQuantity = (nextQuantity: number | string) => {
        if (processing) {
            return;
        }

        router.patch(
            update.url({ product: product.slug }),
            { quantity: nextQuantity },
            {
                preserveScroll: true,
                onStart: () => setProcessing(true),
                onSuccess: (page) => {
                    const cart = page.props.cart as unknown as Cart;
                    const updatedItem = cart.items.find(
                        (item) => item.product.id === product.id,
                    );

                    setValue(String(updatedItem?.quantity ?? quantity));
                    onSuccess(page);
                },
                onError: () => setValue(String(quantity)),
                onFinish: () => setProcessing(false),
            },
        );
    };

    const saveTypedQuantity = () => {
        if (value !== String(quantity)) {
            saveQuantity(value);
        }
    };

    return (
        <div className="flex flex-wrap items-end gap-2">
            <Button
                type="button"
                size="sm"
                variant="outline"
                disabled={quantity <= 1 || processing}
                onClick={() => saveQuantity(quantity - 1)}
                aria-label={`${product.name}を1点減らす`}
            >
                −
            </Button>
            <label className="grid gap-1 text-sm">
                数量
                <Input
                    type="number"
                    name="quantity"
                    value={value}
                    min="1"
                    max={product.stock}
                    inputMode="numeric"
                    className="h-9 w-20 tabular-nums"
                    disabled={processing}
                    onChange={(event) => setValue(event.target.value)}
                    onBlur={saveTypedQuantity}
                    onKeyDown={(event) => {
                        if (event.key === 'Enter') {
                            event.preventDefault();
                            saveTypedQuantity();
                        }
                    }}
                    aria-label={`${product.name}の数量`}
                />
            </label>
            <Button
                type="button"
                size="sm"
                variant="outline"
                disabled={quantity >= product.stock || processing}
                onClick={() => saveQuantity(quantity + 1)}
                aria-label={`${product.name}を1点増やす`}
            >
                +
            </Button>
        </div>
    );
}
