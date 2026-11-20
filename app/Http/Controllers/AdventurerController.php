<?php

namespace App\Http\Controllers;

use App\Models\Adventurer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AdventurerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->role === 'Adventurer', 403);

        if ($user->adventurer()->exists()) {
            return redirect()->route('adventurer.show');
        }

        return view('adventurers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        abort_unless($user->role === 'Adventurer', 403);

        if ($user->adventurer()->exists()) {
            return redirect()->route('adventurer.show')
                ->with('error', 'Profile already exists.');
        }

        $validated = $request->validate([
            'experience_level' => [
                'required',
                'in:Beginner,Intermediate,Advanced',
            ],
            'interests' => ['nullable', 'array'],
            'interests.*' => ['string', 'max:100'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $user->adventurer()->create([
            'experience_level' => $validated['experience_level'],
            'interests' => $validated['interests'] ?? null,
            'location' => $validated['location'] ?? null,
        ]);

        return redirect()->route('adventurer.show')
            ->with('success', 'Profile created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->role === 'Adventurer', 403);

        $adventurer = $user->adventurer;

        if (!$adventurer) {
            return redirect()
                ->route('adventurer.create')
                ->with('error', 'Please create your profile first.');
        }

        return view('adventurers.show', compact('adventurer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->role === 'Adventuretr', 403);

        $adventurer = $user->adventurer;

        if (!$adventurer) {
            return redirect()->route('adventurer.create')
                ->with('error', 'Please create your profile first.');
        }

        return view('adventurers.edit', compact('adventurer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->role === 'Adventurer', 403);

        $adventurer = $user->adventurer;

        if (!$adventurer) {
            return redirect()
                ->route('adventurer.create')
                ->with('error', 'Please create your profile first.');
        }

        $validated = $request->validate([
            'experience_level' => [
                'required',
                'in:Beginner,Intermediate,Advanced',
            ],
            'interests' => ['nullable', 'array'],
            'interests.*' => ['string', 'max:100'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $adventurer->update([
            'experience_level' => $validated['experience_level'],
            'interests' => $validated['interests'] ?? null,
            'location' => $validated['location'] ?? null,
        ]);

        return redirect()
            ->route('adventurer.show')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Adventurer $adventurer)
    {
        //
    }
}
