<?php

namespace App\Http\Controllers;

use App\Models\Guides_availabilities;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GuidesAvailabilitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organizerId = (int) request()->user()->activityOrganizer->id;
        $availabilities = Guides_availabilities::with('guide')
            ->whereHas('guide', fn ($query) => $query->where('activity_organizer_id', $organizerId))
            ->get();

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
            'guide_id' => [
                'required',
                Rule::exists('guides', 'id')->where('activity_organizer_id', $request->user()->activityOrganizer->id),
                'unique:guides_availabilities,guide_id',
            ],
            'status' => 'required|in:Available,Busy',
        ]);

        Guides_availabilities::create($validated);

        return redirect()->route('guide-availabilities.index')
            ->with('success', 'Guide availability created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $availability = $this->ownedAvailability($id);

        return view('guide_availabilities.show', compact('availability'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $availability = $this->ownedAvailability($id);

        return view('guide_availabilities.edit', compact('availability'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $availability = $this->ownedAvailability($id);

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
        $availability = $this->ownedAvailability($id);

        $availability->delete();

        return redirect()->route('guide-availabilities.index')
            ->with('success', 'Guide availability deleted successfully');
    }

    private function ownedAvailability(int $id): Guides_availabilities
    {
        $organizerId = (int) request()->user()->activityOrganizer->id;

        return Guides_availabilities::whereHas(
            'guide',
            fn ($query) => $query->where('activity_organizer_id', $organizerId)
        )->findOrFail($id);
    }
}
