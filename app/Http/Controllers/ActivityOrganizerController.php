<?php

namespace App\Http\Controllers;

use App\Models\ActivityOrganizer;
use Illuminate\Http\Request;

class ActivityOrganizerController extends Controller
{
    // --- Organizer Side ---
    /**
     * organizer dashboard
     */
    public function dashboard()
    {
        $organizer = auth()->user()->activity_organizer;
        return view('organizer.dashboard', compact('organizer'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ActivityOrganizer $activityOrganizer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ActivityOrganizer $activityOrganizer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ActivityOrganizer $activityOrganizer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActivityOrganizer $activityOrganizer)
    {
        //
    }
}
