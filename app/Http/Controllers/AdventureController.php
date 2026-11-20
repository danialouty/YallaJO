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
        $adventures = Adventure::latest()->paginate(5);
        return view('adventures.index',compact('adventures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('adventures.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'required|string|max:255',
            'activity_organizer_id'=>'required',
            'difficulty_level'=>'required',
            'capacity'=>'required',
            'category'=>'required',
            'location'=>'required|string|max:255',
            'duration_hours'=>'required',
            'price'=>'required',
            'requirements' => 'required|string',
            'status'=>'required',
        ]);
        $input = $request->all();
        Adventure::create($input);
        return redirect()->route('adventures.index')
            ->with('success', 'Adventure created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Adventure $adventure)
    {
        //
        return view('adventures.show', compact('adventure'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Adventure $adventure)
    {
        //
        return view('adventures.edit', compact('adventure'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adventure $adventure)
    {
        //
        $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'required|string|max:255',
            'activity_organizer_id'=>'required',
            'difficulty_level'=>'required',
            'capacity'=>'required',
            'category'=>'required',
            'location'=>'required|string|max:255',
            'duration_hours'=>'required',
            'price'=>'required',
            'requirements' => 'required|string',
            'status'=>'required',
        ]);

        $adventure->update([
            'title' => $request->title,
            'description' => $request->description,
            'activity_organizer_id'=>$request->activity_organizer_id,
            'difficulty_level'=>$request->difficulty_level,
            'capacity'=>$request->capacity,
            'category'=>$request->category,
            'location'=>$request->location,
            'duration_hours'=>$request->duration_hours,
            'price'=>$request->price,
            'status'=>$request->status,
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
}
