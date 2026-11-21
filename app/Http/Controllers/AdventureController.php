<?php

namespace App\Http\Controllers;

use App\Models\Adventure;
use Illuminate\Http\Request;

class AdventureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $organizerId = (int) request()->user()->activityOrganizer->id;
        $adventures = Adventure::with(['images', 'schedules'])
            ->where('activity_organizer_id', $organizerId)
            ->latest()->paginate(5);
        $optionalAdventure = request('phase') === 'optional'
            ? Adventure::with(['images', 'schedules'])
                ->where('activity_organizer_id', $organizerId)
                ->find(request('adventure_id'))
            : null;

        return view('adventures.index', compact('adventures', 'optionalAdventure'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('adventures.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'difficulty_level' => 'required',
            'capacity' => 'required',
            'category' => 'required',
            'location' => 'required|string|max:255',
            'duration_hours' => 'required',
            'price' => 'required',
            'requirements' => 'required|string',
        ]);
        $validated['activity_organizer_id'] = $request->user()->activityOrganizer->id;
        $adventure = Adventure::create($validated);

        return redirect()->route('adventures.index', [
            'phase' => 'optional',
            'adventure_id' => $adventure->id,
        ])
            ->with('success', 'Adventure created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Adventure $adventure)
    {
        $this->ensureOwnedByCurrentOrganizer($adventure);
        $adventure->load(['images', 'schedules']);
        return view('adventures.show', compact('adventure'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Adventure $adventure)
    {
        $this->ensureOwnedByCurrentOrganizer($adventure);
        $adventure->load(['images', 'schedules']);

        return view('adventures.edit', compact('adventure'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adventure $adventure)
    {
        $this->ensureOwnedByCurrentOrganizer($adventure);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'difficulty_level' => 'required',
            'capacity' => 'required',
            'category' => 'required',
            'location' => 'required|string|max:255',
            'duration_hours' => 'required',
            'price' => 'required',
            'requirements' => 'required|string',
            'status' => 'required',
        ]);

        $adventure->update($validated);

        return redirect()->route('adventures.index')
            ->with('success', 'Adventure updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Adventure $adventure)
    {
        $this->ensureOwnedByCurrentOrganizer($adventure);
        $adventure->delete();
        return redirect()->route('adventures.index')
            ->with('success', 'Adventure deleted successfully');
    }

    public function indexAll(Request $request)
    {
        $query = Adventure::where('status', 'Active');

        // Search by adventure name
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $adventures = $query->latest()->paginate(12)->withQueryString();

        $locations = Adventure::select('location')
            ->distinct()
            ->pluck('location');

        $categories = Adventure::select('category')
            ->distinct()
            ->pluck('category');

        return view('adventures', compact(
            'adventures',
            'locations',
            'categories'
        ));
    }

    public function showProfile(Adventure $adventure)
    {
        $adventure->load(['images', 'days']);

        return view('adventureProfile', compact('adventure'));
    }

    private function ensureOwnedByCurrentOrganizer(Adventure $adventure): void
    {
        abort_unless(
            (int) $adventure->activity_organizer_id === (int) request()->user()->activityOrganizer->id,
            404
        );
    }
}
