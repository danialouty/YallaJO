<?php

namespace App\Http\Controllers;

use App\Models\Guides;
use Illuminate\Http\Request;

class GuidesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guides = Guides::all();

        return view('guides.index', compact('guides'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('guides.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_organizer_id' => 'required|exists:activity_organizers,id',
            'bio' => 'required|string',
            'experience_years' => 'required|integer|min:0',
            'specialization' => 'required|array',
            'specialization.*' => 'string',
            'cetification' => 'nullable|string',
            'status' => 'sometimes|in:Available,Busy',
        ]);

        $guide = Guides::create($validated);

        return redirect()->route('guides.index')
    ->with('success', 'Guide created successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $guide = Guides::findOrFail($id);

        return view('guides.show', compact('guide'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $guide = Guides::findOrFail($id);

    return view('guides.edit', compact('guide'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $guide = Guides::findOrFail($id);

        $validated = $request->validate([
            'activity_organizer_id' => 'sometimes|required|exists:activity_organizers,id',
            'bio' => 'sometimes|required|string',
            'experience_years' => 'sometimes|required|integer|min:0',
            'specialization' => 'sometimes|required|array',
            'specialization.*' => 'string',
            'cetification' => 'nullable|string',
            'status' => 'sometimes|required|in:Available,Busy',
        ]);

        $guide->update($validated);

        return redirect()->route('guides.index')
        ->with('success', 'Guide updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $guide = Guides::findOrFail($id);

        $guide->delete();

        return response()->json([
            'message' => 'Guide deleted successfully'
        ]);
    }
}
