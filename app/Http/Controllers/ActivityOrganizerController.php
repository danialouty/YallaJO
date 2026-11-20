<?php

namespace App\Http\Controllers;

use App\Models\ActivityOrganizer;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

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
    public function index(Request $request) : View
    {
        //
        $organizer = $request->user()->activityOrganizer;

        return view('organizer.dashboard', compact('organizer'));
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
    public function store(Request $request) : RedirectResponse
    {
        //
        abort_if($request->user()->activityOrganizer, 403, 'Organizer profile already exists.');

        $validated = $this->validateProfile($request);

        $request->user()->activityOrganizer()->create([
            ...$validated,
            'approval_status' => 'pending',
        ]);

        return redirect()
            ->route('organizers.index')
            ->with('status', 'Profile submitted! Your organizer account is pending admin verification.');
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
    public function update(Request $request, ActivityOrganizer $organizer)
    {
        //
        abort_if($organizer->user_id !== $request->user()->id, 403);

        $validated = $this->validateProfile($request);

        if ($organizer->approval_status === 'rejected') {
            $validated['approval_status'] = 'pending';
            $validated['approved_by'] = null;
            $validated['approved_at'] = null;
        }

        $organizer->update($validated);

        return redirect()
            ->route('organizers.index')
            ->with('status', 'Organizer profile updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActivityOrganizer $activityOrganizer)
    {
        //
    }

    private function validateProfile(Request $request): array
    {
        return $request->validate([
            'organizer_type'    => ['required', 'in:tourism_office,hike_group'],
            'license_number'    => ['nullable', 'required_if:organizer_type,tourism_office', 'string', 'max:100'],
            'social_handle_url' => ['nullable', 'url', 'max:255'],
        ], [
            'license_number.required_if' => 'A Ministry of Tourism license number is required for registered tourism offices.',
        ]);
    }
}
