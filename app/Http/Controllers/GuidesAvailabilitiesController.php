<?php

namespace App\Http\Controllers;

use App\Models\Guides_availabilities;
use App\Models\Guides;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'guide_id' => 'required|exists:guides,id|unique:guides_availabilities,guide_id',
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

        $availability->delete();

        return redirect()->route('guide-availabilities.index')
            ->with('success', 'Guide availability deleted successfully');
    }
}
