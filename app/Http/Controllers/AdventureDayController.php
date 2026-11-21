<?php

namespace App\Http\Controllers;

use App\Models\Adventure;
use App\Models\AdventureDay;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdventureDayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adventureDays = AdventureDay::with('adventure')
            ->orderBy('adventure_id')
            ->orderBy('day_number')
            ->paginate(15);

        return view('adventure_days.index', compact('adventureDays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $adventures = Adventure::orderBy('title')->get();

        return view('adventure_days.create', compact('adventures'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'adventure_id' => ['required', 'integer', 'exists:adventures,id'],
            'day_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('adventure_days', 'day_number')
                    ->where(fn ($query) => $query->where('adventure_id', $request->integer('adventure_id'))),
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        AdventureDay::create($validated);

        return redirect()->route('adventure-days.index')
            ->with('success', 'Adventure day created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(AdventureDay $adventureDay)
    {
        $adventureDay->load('adventure');

        return view('adventure_days.show', compact('adventureDay'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdventureDay $adventureDay)
    {
        $adventures = Adventure::orderBy('title')->get();

        return view('adventure_days.edit', compact('adventureDay', 'adventures'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdventureDay $adventureDay)
    {
        $validated = $request->validate([
            'adventure_id' => ['required', 'integer', 'exists:adventures,id'],
            'day_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('adventure_days', 'day_number')
                    ->where(fn ($query) => $query->where('adventure_id', $request->integer('adventure_id')))
                    ->ignore($adventureDay->id),
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $adventureDay->update($validated);

        return redirect()->route('adventure-days.index')
            ->with('success', 'Adventure day updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdventureDay $adventureDay)
    {
        $adventureDay->delete();

        return redirect()->route('adventure-days.index')
            ->with('success', 'Adventure day deleted successfully');
    }
}
