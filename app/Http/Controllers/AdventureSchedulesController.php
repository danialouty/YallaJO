<?php

namespace App\Http\Controllers;

use App\Models\Adventure_schedules;
use Illuminate\Http\Request;

class AdventureSchedulesController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'adventure_id'=>'required',
            'guide_id'=>'required',
            'starts_at'=>'required',
            'ends_at'=>'required',
            'capacity'=>'required',
            'status' => 'required',
        ]);

        $input = $request->all();
        Adventure_schedules::create($input);
        return redirect()->route('adventures.index')
            ->with('success', 'Adventure created successfully');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adventure_schedules $adventure_schedules)
    {
        //
        $request->validate([
            'adventure_id'=>'required',
            'guide_id'=>'required',
            'starts_at'=>'required',
            'ends_at'=>'required',
            'capacity'=>'required',
            'status' => 'required',
        ]);

        $input = $request->all();
        $adventure_schedules->update($input);
        return redirect()->route('adventures.index')
            ->with('success', 'Adventure updated successfully');
    }

}
