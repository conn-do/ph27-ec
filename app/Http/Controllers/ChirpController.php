<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChirpRequest;
use App\Models\Chirp;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ChirpController extends Controller
{
    public function index(): Response
    {
        $chirps = Chirp::query()
            ->with('user:id,name')
            ->latest()
            ->get()
            ->map(fn(Chirp $chirp): array => [
                'id' => $chirp->id,
                'message' => $chirp->message,
                'created_at' => $chirp->created_at?->diffForHumans(),
                'user' => [
                    'name' => $chirp->user->name,
                ],
            ]);

        return Inertia::render('chirps/index', [
            'chirps' => $chirps,
        ]);
    }

    public function store(StoreChirpRequest $request): RedirectResponse
    {
        $request->user()->chirps()->create($request->validated());

        return to_route('chirps.index');
    }
}
