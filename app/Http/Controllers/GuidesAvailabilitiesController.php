<?php

namespace App\Http\Controllers;

use App\Models\Guides;
use App\Models\Guides_availabilities;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class GuidesAvailabilitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $availabilities = $this->ownedAvailabilities()->with('guide')->get();

        return view('guide_availabilities.index', compact('availabilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->index();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'guide_id' => 'required|integer|exists:guides,id|unique:guides_availabilities,guide_id',
            'status' => 'required|in:Available,Busy',
        ]);

        $guide = Guides::where('activity_organizer_id', $request->user()->activityOrganizer->id)
            ->findOrFail($validated['guide_id']);
        $guide->availability()->create(['status' => $validated['status']]);

        return redirect()->route('guide-availabilities.index')
            ->with('success', 'Guide availability created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $availability = $this->ownedAvailabilities()->with('guide')->findOrFail($id);

        return view('guide_availabilities.show', compact('availability'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $availability = $this->ownedAvailabilities()->findOrFail($id);

        return view('guide_availabilities.edit', compact('availability'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $availability = $this->ownedAvailabilities()->findOrFail($id);

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
        $availability = $this->ownedAvailabilities()->findOrFail($id);

        $availability->delete();

        return redirect()->route('guide-availabilities.index')
            ->with('success', 'Guide availability deleted successfully');
    }

    private function ownedAvailabilities(): Builder
    {
        return Guides_availabilities::query()->whereHas('guide', fn (Builder $query) => $query
            ->where('activity_organizer_id', auth()->user()->activityOrganizer->id));
    }
}
