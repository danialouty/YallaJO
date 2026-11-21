<?php

namespace App\Http\Controllers;

use App\Models\ActivityOrganizer;
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
        $adventures = Adventure::with(['images', 'schedules'])->latest()->paginate(5);
        $optionalAdventure = request('phase') === 'optional'
            ? Adventure::with(['images', 'schedules'])->find(request('adventure_id'))
            : null;

        return view('adventures.index', compact('adventures', 'optionalAdventure'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organizers = ActivityOrganizer::all();
        return view('adventures.create', compact('organizers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'activity_organizer_id' => 'required',
            'difficulty_level' => 'required',
            'capacity' => 'required',
            'category' => 'required',
            'location' => 'required|string|max:255',
            'duration_hours' => 'required',
            'price' => 'required',
            'requirements' => 'required|string',
        ]);
        $adventure = Adventure::create($request->only([
            'title',
            'description',
            'activity_organizer_id',
            'difficulty_level',
            'capacity',
            'category',
            'location',
            'duration_hours',
            'price',
            'requirements',
        ]));

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
        //
        $adventure->load(['images', 'schedules']);
        return view('adventures.show', compact('adventure'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Adventure $adventure)
    {
        $adventure->load(['images', 'schedules']);

        return view('adventures.edit', compact('adventure'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adventure $adventure)
    {
        //
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'activity_organizer_id' => 'required',
            'difficulty_level' => 'required',
            'capacity' => 'required',
            'category' => 'required',
            'location' => 'required|string|max:255',
            'duration_hours' => 'required',
            'price' => 'required',
            'requirements' => 'required|string',
            'status' => 'required',
        ]);

        $adventure->update([
            'title' => $request->title,
            'description' => $request->description,
            'activity_organizer_id' => $request->activity_organizer_id,
            'difficulty_level' => $request->difficulty_level,
            'capacity' => $request->capacity,
            'category' => $request->category,
            'location' => $request->location,
            'duration_hours' => $request->duration_hours,
            'price' => $request->price,
            'requirements' => $request->requirements,
            'status' => $request->status,
        ]);

        return redirect()->route('adventures.index')
            ->with('success', 'Adventure updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Adventure $adventure)
    {
        //
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
}
