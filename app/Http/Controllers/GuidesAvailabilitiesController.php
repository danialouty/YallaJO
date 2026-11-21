<?php

namespace App\Http\Controllers;

use App\Models\Guides_availabilities;
use App\Models\Guides;
use App\Models\ActivityOrganizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuidesAvailabilitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $availabilities = Guides_availabilities::with('guide')->get();

        return view('guide_availabilities.index', compact('availabilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $availabilities = Guides_availabilities::with('guide')->get();

        return view('guide_availabilities.index', compact('availabilities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $organizer = ActivityOrganizer::where('user_id', Auth::id())->first();
        abort_unless($organizer, 403);

        $validated = $request->validate([
            'status' => 'required|in:Available,Busy',
        ]);

        $guide = Guides::where('activity_organizer_id', $organizer->id)->firstOrFail();
        abort_if(Guides_availabilities::where('guide_id', $guide->id)->exists(), 409);

        Guides_availabilities::create([
            'guide_id' => $guide->id,
            'status' => $validated['status'],
        ]);

        return redirect()->route('guide-availabilities.index')
            ->with('success', 'Guide availability created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $availability = Guides_availabilities::with('guide')->findOrFail($id);

        return view('guide_availabilities.show', compact('availability'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $availability = Guides_availabilities::findOrFail($id);

        return view('guide_availabilities.edit', compact('availability'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $availability = Guides_availabilities::findOrFail($id);
        $organizer = ActivityOrganizer::where('user_id', Auth::id())->first();
        abort_unless(
            $organizer
            && $availability->guide
            && $availability->guide->activity_organizer_id === $organizer->id,
            403
        );

        $validated = $request->validate([
            'status' => 'sometimes|required|in:Available,Busy',
        ]);

        $availability->update($validated);

        return redirect()->route('guide-availabilities.index')
            ->with('success', 'Guide availability updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $availability = Guides_availabilities::findOrFail($id);
        $organizer = ActivityOrganizer::where('user_id', Auth::id())->first();
        abort_unless(
            $organizer
            && $availability->guide
            && $availability->guide->activity_organizer_id === $organizer->id,
            403
        );

        $availability->delete();

        return redirect()->route('guide-availabilities.index')
            ->with('success', 'Guide availability deleted successfully');
    }
}
