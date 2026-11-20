<?php

namespace App\Http\Controllers;

use App\Models\Adventure_schedules;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdventureSchedulesController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'adventure_id'=>'required|exists:adventures,id',
            'guide_id'=>'required|exists:guides,id',
            'starts_at'=>'required|date',
            'ends_at'=>'required|date|after:starts_at',
            'capacity'=>'required|integer|min:1',
            'status' => ['required', Rule::in(['Active', 'Suspended'])],
        ]);

        Adventure_schedules::create($request->only([
            'adventure_id', 'guide_id', 'starts_at', 'ends_at', 'capacity', 'status',
        ]));
        $redirect = $request->input('_form_type') === 'optional'
            ? redirect()->route('adventures.index', [
                'phase' => 'optional',
                'adventure_id' => $request->adventure_id,
            ])
            : redirect()->route('adventures.edit', $request->adventure_id);

        return $redirect
            ->with('success', 'Adventure created successfully');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adventure_schedules $adventure_schedules)
    {
        //
        $request->validate([
            'adventure_id'=>'required|exists:adventures,id',
            'guide_id'=>'required|exists:guides,id',
            'starts_at'=>'required|date',
            'ends_at'=>'required|date|after:starts_at',
            'capacity'=>'required|integer|min:1',
            'status' => ['required', Rule::in(['Active', 'Suspended'])],
        ]);

        $adventure_schedules->update($request->only([
            'guide_id', 'starts_at', 'ends_at', 'capacity', 'status',
        ]));
        return redirect()->route('adventures.edit', $adventure_schedules->adventure_id)
            ->with('success', 'Adventure updated successfully');
    }

}
