import { usePage } from '@inertiajs/react';

export default function FlashMessages() {
    const { flash } = usePage().props;

    return (
        <div className="mx-auto max-w-6xl space-y-3 px-4 pt-4 sm:px-6">
            {flash.success && (
                <p
                    role="status"
                    className="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-200"
                >
                    {flash.success}
                </p>
            )}
            {flash.error && (
                <p
                    role="alert"
                    className="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-300"
                >
                    {flash.error}
                </p>
            )}
        </div>
    );
}
