import { ImageIcon } from 'lucide-react';
import { useState } from 'react';
import { cn } from '@/lib/utils';

type Props = {
    src: string | null;
    alt: string;
    className?: string;
    loading?: 'lazy' | 'eager';
};

export default function ProductImage({
    src,
    alt,
    className,
    loading = 'lazy',
}: Props) {
    const [failedSource, setFailedSource] = useState<string | null>(null);

    return (
        <div
            className={cn('aspect-square overflow-hidden bg-muted', className)}
        >
            {src && src !== failedSource ? (
                <img
                    src={src}
                    alt={alt}
                    loading={loading}
                    decoding="async"
                    onError={() => setFailedSource(src)}
                    className="size-full object-cover"
                />
            ) : (
                <div
                    role="img"
                    aria-label={`${alt}（画像準備中）`}
                    className="flex size-full flex-col items-center justify-center gap-3 text-muted-foreground"
                >
                    <ImageIcon className="size-9" aria-hidden="true" />
                    <span className="text-sm" aria-hidden="true">
                        画像準備中
                    </span>
                </div>
            )}
        </div>
    );
}
