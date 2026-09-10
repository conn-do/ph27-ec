<x-layout>
    <x-slot:title>
        Chirps
    </x-slot:title>

    <div class="max-w-2xl mx-auto">

        <div class="card bg-base-100 shadow">
            <div class="card-body">

                <h1 class="text-3xl font-bold">
                    Chirper
                </h1>

            <form method="POST" action="{{ route('chirps.store') }}" class="mt-6">
                @csrf

                <textarea
                    name="message"
                    class="textarea textarea-bordered w-full"
                    placeholder="What's happening?"
                >{{ is_string(old('message')) ? old('message') : '' }}</textarea>

                @error('message')
                    <p class="mt-2 text-sm text-red-600" role="alert">{{ $message }}</p>
                @enderror

                <button class="btn btn-primary mt-4">
                    Chirp
                </button>
            </form>
            <div class="mt-8 space-y-4">

                @foreach ($chirps as $chirp)

                    <div class="card bg-base-100 shadow">

                        <div class="card-body">

                            <div class="card bg-base-100 shadow">

                                <div class="card-body">

                                    <div class="flex items-start gap-4">

                                        <div class="avatar">

                                            <div class="w-12 rounded-full">

                                                <img
                                                    src="https://api.dicebear.com/7.x/adventurer/svg?seed={{ $chirp->user->name }}"
                                                    alt="avatar"
                                                />

                                            </div>

                                        </div>

                                        <div class="flex-1">

                                            <h2 class="font-bold">
                                                {{ $chirp->user->name }}
                                            </h2>

                                            <p class="mt-2 text-lg">
                                                {{ $chirp->message }}
                                            </p>

                                            <p class="text-sm text-base-content/60 mt-2">
                                                {{ $chirp->created_at }}
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <p class="text-sm text-base-content/60">
                                {{ $chirp->created_at }}
                            </p>

                        </div>

                    </div>

                @endforeach

            </div>

            </div>
        </div>

    </div>
</x-layout>
