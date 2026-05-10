<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chirp;

class ChirpController extends Controller
{
    public function index()
    {
        $chirps = Chirp::with('user')
            ->latest()
            ->take(50)  // Limit to 50 most recent chirps
            ->get();

        return view('home', ['chirps' => $chirps]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ], [
            'message.required' => 'Please write something to chirp!',
            'message.max' => 'Chirps must be 255 characters or less.',
        ]);

        Chirp::create([
            'message' => $validated['message'],
            'user_id' => $request->user()?->id,
        ]);

        return redirect('/chirps')->with('success', 'Your chirp has been posted!');
    }

    public function edit(Chirp $chirp)
    {
        // We'll add authorization in lesson 11
        return view('edit', compact('chirp'));
    }

    public function update(Request $request, Chirp $chirp)
    {
        if (! $request->user() || $request->user()->cannot('update', $chirp)) {
            abort(403);
        }

        // Validate
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        // Update
        $chirp->message = $validated['message'];
        $chirp->save();

        return redirect('/chirps')->with('success', 'Chirp updated!');
    }

    public function destroy(Request $request, Chirp $chirp)
    {
        if (! $request->user() || $request->user()->cannot('delete', $chirp)) {
            abort(403);
        }

        $chirp->delete($chirp->id);

        return redirect('/chirps')->with('success', 'Chirp deleted!');
    }
}
