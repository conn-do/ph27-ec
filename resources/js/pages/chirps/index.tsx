import { Form, Head } from '@inertiajs/react';
import { MessageCircle } from 'lucide-react';
import InputError from '@/components/input-error';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { store } from '@/routes/chirps';
import type { BreadcrumbItem } from '@/types';

type Chirp = {
    id: number;
    message: string;
    created_at: string | null;
    user: {
        name: string;
    };
};

export default function ChirpsIndex({ chirps }: { chirps: Chirp[] }) {
    return (
        <>
            <Head title="Chirps" />

            <div className="-m-4 min-h-[calc(100vh-5rem)] bg-white text-black sm:-m-6">
                <div className="mx-auto flex w-full max-w-3xl flex-col gap-6 px-4 py-6 sm:px-6">
                    <div className="space-y-1 border-b border-black pb-5">
                        <p className="text-sm font-medium tracking-normal text-black uppercase">
                            PH27 Homework
                        </p>
                        <h1 className="text-3xl font-semibold text-black">
                            Chirper
                        </h1>
                    </div>

                    <Card className="rounded-lg border-black bg-white text-black shadow-none">
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2 text-xl">
                                <MessageCircle className="size-5" />
                                New chirp
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <Form
                                {...store.form()}
                                resetOnSuccess
                                className="flex flex-col gap-4"
                            >
                                {({ errors, processing }) => (
                                    <>
                                        <textarea
                                            name="message"
                                            rows={3}
                                            autoFocus
                                            placeholder="What's on your mind?"
                                            className="min-h-24 w-full resize-y rounded-md border border-black bg-white px-3 py-2 text-sm text-black shadow-none transition-[color,box-shadow] outline-none placeholder:text-neutral-500 focus-visible:ring-[3px] focus-visible:ring-black/20 disabled:cursor-not-allowed disabled:opacity-50"
                                        />

                                        <InputError message={errors.message} />

                                        <div className="flex justify-end">
                                            <Button
                                                type="submit"
                                                disabled={processing}
                                                className="bg-black text-white hover:bg-neutral-800"
                                            >
                                                Chirp
                                            </Button>
                                        </div>
                                    </>
                                )}
                            </Form>
                        </CardContent>
                    </Card>

                    <div className="flex flex-col gap-3">
                        {chirps.length === 0 ? (
                            <p className="rounded-lg border border-dashed border-black bg-white p-6 text-center text-sm text-black">
                                No chirps yet.
                            </p>
                        ) : (
                            chirps.map((chirp) => (
                                <Card
                                    key={chirp.id}
                                    className="rounded-lg border-black bg-white py-5 text-black shadow-none"
                                >
                                    <CardContent className="flex gap-4">
                                        <Avatar>
                                            <AvatarFallback className="bg-black text-white">
                                                {chirp.user.name
                                                    .slice(0, 2)
                                                    .toUpperCase()}
                                            </AvatarFallback>
                                        </Avatar>

                                        <div className="min-w-0 flex-1 space-y-1">
                                            <div className="flex flex-wrap items-baseline gap-x-2 gap-y-1">
                                                <p className="font-medium">
                                                    {chirp.user.name}
                                                </p>
                                                {chirp.created_at && (
                                                    <p className="text-sm text-neutral-600">
                                                        {chirp.created_at}
                                                    </p>
                                                )}
                                            </div>

                                            <p className="text-sm leading-6 break-words">
                                                {chirp.message}
                                            </p>
                                        </div>
                                    </CardContent>
                                </Card>
                            ))
                        )}
                    </div>
                </div>
            </div>
        </>
    );
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Chirps',
        href: '/chirps',
    },
];

ChirpsIndex.layout = {
    breadcrumbs,
};
