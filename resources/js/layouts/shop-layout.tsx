import { Link, usePage } from '@inertiajs/react';
import type { ReactNode } from 'react';
import FlashMessages from '@/components/flash-messages';
import { dashboard, home, login, shop } from '@/routes';
import { index as cartIndex } from '@/routes/cart';
import { index as ordersIndex } from '@/routes/orders';

export default function ShopLayout({ children }: { children: ReactNode }) {
    const { auth, cartQuantity } = usePage().props;

    return (
        <div className="sugoi-shop min-h-svh text-foreground">
            <a
                href="#shop-main"
                className="sr-only focus:not-sr-only focus:absolute focus:top-3 focus:left-3 focus:z-50 focus:rounded-full focus:bg-primary focus:px-4 focus:py-2 focus:text-primary-foreground"
            >
                本文へスキップ
            </a>
            <header className="sticky top-0 z-40 border-b border-[#ded5c6]/80 bg-[#f9f6ef]/90 backdrop-blur-xl">
                <div className="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-x-6 gap-y-3 px-5 py-4 sm:px-8 lg:px-10">
                        <Link href={home()} className="block shrink-0">
                            <img
                                src="/images/brand/sugoi-stationery-logo.png"
                                alt="すごい文房具"
                                className="h-11 w-auto object-contain mix-blend-multiply sm:h-12"
                            />
                        </Link>
                    <nav
                        aria-label="ショップナビゲーション"
                        className="order-3 flex w-full items-center justify-between gap-3 overflow-x-auto whitespace-nowrap border-t border-[#ded5c6]/70 pt-3 text-xs font-semibold sm:order-2 sm:w-auto sm:border-0 sm:pt-0 sm:text-sm"
                    >
                        <Link href={home()} className="transition hover:text-[#a87632]">
                            ホーム
                        </Link>
                        <Link href={shop()} className="transition hover:text-[#a87632]">
                            商品一覧
                        </Link>
                        {auth.user && (
                            <Link
                                href={ordersIndex()}
                                className="transition hover:text-[#a87632]"
                            >
                                注文履歴
                            </Link>
                        )}
                        <Link
                            href={cartIndex()}
                            className="rounded-full bg-[#101827] px-3 py-1.5 text-[#fffaf1] transition hover:bg-[#a87632]"
                        >
                            カート{cartQuantity > 0 ? ` (${cartQuantity})` : ''}
                        </Link>
                    </nav>
                    {auth.user ? (
                        <Link
                            href={dashboard()}
                            className="order-2 text-xs font-semibold text-[#5b6472] transition hover:text-[#a87632] sm:order-3 sm:text-sm"
                        >
                            ユーザー
                        </Link>
                    ) : (
                        <Link
                            href={login()}
                            className="order-2 text-xs font-semibold text-[#5b6472] transition hover:text-[#a87632] sm:order-3 sm:text-sm"
                        >
                            ログイン
                        </Link>
                    )}
                </div>
            </header>
            <FlashMessages />
            <main id="shop-main" className="mx-auto max-w-7xl px-5 py-10 sm:px-8 sm:py-14 lg:px-10">
                {children}
            </main>
            <footer className="mt-12 border-t border-[#ded5c6] bg-[#101827] text-[#fffaf1]">
                <div className="mx-auto flex max-w-7xl flex-col gap-8 px-5 py-10 sm:px-8 md:flex-row md:items-end md:justify-between lg:px-10">
                    <div>
                        <p className="editorial-title text-2xl font-bold text-[#fffaf1]">
                            すごい文房具
                        </p>
                        <p className="mt-3 text-sm leading-6 text-[#d9d2c5]">
                            書くことから、毎日を少し好きになる。
                        </p>
                    </div>
                    <div className="flex gap-5 text-sm text-[#d9d2c5]">
                        <Link href={shop()} className="hover:text-white">
                            商品一覧
                        </Link>
                        <Link href={cartIndex()} className="hover:text-white">
                            カート
                        </Link>
                        {auth.user && (
                            <Link href={ordersIndex()} className="hover:text-white">
                                注文履歴
                            </Link>
                        )}
                    </div>
                </div>
            </footer>
        </div>
    );
}
