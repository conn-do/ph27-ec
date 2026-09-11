{{-- おしらせ・エラーを ぜんぶ おなじ見た目で 出す --}}
@if (session('message'))
    <div class="mb-6 flex items-center gap-3 rounded-3xl border-4 border-ink bg-pop-green px-5 py-4 text-lg font-black text-white shadow-block animate-pop-in">
        <span class="text-2xl" aria-hidden="true">🎉</span>
        <p>{{ session('message') }}</p>
    </div>
@endif

@if (session('error'))
    <div class="mb-6 flex items-center gap-3 rounded-3xl border-4 border-ink bg-pop-red px-5 py-4 text-lg font-black text-white shadow-block animate-pop-in">
        <span class="text-2xl" aria-hidden="true">⚠️</span>
        <p>{{ session('error') }}</p>
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 rounded-3xl border-4 border-ink bg-pop-yellow px-5 py-4 shadow-block animate-pop-in">
        <p class="mb-2 flex items-center gap-2 text-lg font-black">
            <span class="text-2xl" aria-hidden="true">🤔</span>
            ちょっと まって！
        </p>
        <ul class="space-y-1 pl-9 text-base font-bold">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
