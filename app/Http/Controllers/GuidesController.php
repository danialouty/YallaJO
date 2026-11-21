<?php

namespace App\Http\Controllers;

use App\Models\Guides;
use App\Models\ActivityOrganizer;
use App\Models\Guides_availabilities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuidesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organizer = ActivityOrganizer::where('user_id', Auth::id())->firstOrFail();
        $guide = $organizer
            ? Guides::with([
                'activityOrganizer.user',
                'availability',
                'schedules.adventure',
            ])->where('activity_organizer_id', $organizer->id)->first()
            : null;

        $schedules = $guide
            ? $guide->schedules->sortBy('starts_at')
            : collect();
        $activeSchedules = $schedules->where('status', 'Active')->count();
        $suspendedSchedules = $schedules->where('status', 'Suspended')->count();

        return view('guides.index', compact(
            'guide',
            'organizer',
            'schedules',
            'activeSchedules',
            'suspendedSchedules'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organizer = ActivityOrganizer::where('user_id', Auth::id())->firstOrFail();
        abort_if(Guides::where('activity_organizer_id', $organizer->id)->exists(), 403, 'A guide profile already exists for this organizer.');

        return view('guides.create', compact('organizer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bio' => 'required|string',
            'experience_years' => 'required|integer|min:0',
            'specialization' => 'required|string',
            'cetification' => 'nullable|string',
            'status' => 'sometimes|in:Available,Busy',
        ]);
        $validated['specialization'] = array_values(array_filter(array_map('trim', explode(',', $validated['specialization']))));
        $organizer = ActivityOrganizer::where('user_id', Auth::id())->firstOrFail();
        abort_if(Guides::where('activity_organizer_id', $organizer->id)->exists(), 403, 'A guide profile already exists for this organizer.');
        $validated['activity_organizer_id'] = $organizer->id;

        $guide = Guides::create($validated);

        return redirect()->route('guides.index')
    ->with('success', 'تمت إضافة المرشد بنجاح');

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $guide = Guides::with(['activityOrganizer.user', 'availability', 'schedules.adventure'])->findOrFail($id);

        return view('guides.show', compact('guide'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $guide = Guides::findOrFail($id);
        $organizers = ActivityOrganizer::with('user')->get();

    return view('guides.edit', compact('guide', 'organizers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $guide = Guides::findOrFail($id);
        abort_unless(
            ActivityOrganizer::where('id', $guide->activity_organizer_id)
                ->where('user_id', Auth::id())
                ->exists(),
            403,
            'لا تملك صلاحية تعديل هذا المرشد'
        );

        $validated = $request->validate([
            'bio' => 'sometimes|required|string',
            'experience_years' => 'sometimes|required|integer|min:0',
            'specialization' => 'sometimes|required|string',
            'cetification' => 'nullable|string',
            'status' => 'sometimes|required|in:Available,Busy',
        ]);
        if (isset($validated['specialization'])) {
            $validated['specialization'] = array_values(array_filter(array_map('trim', explode(',', $validated['specialization']))));
        }

        $guide->update($validated);
        if (isset($validated['status'])) {
            Guides_availabilities::updateOrCreate(
                ['guide_id' => $guide->id],
                ['status' => $validated['status']]
            );
        }

        return redirect()->route('guides.index')
        ->with('success', 'تم تعديل بيانات المرشد بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $organizer = ActivityOrganizer::where('user_id', Auth::id())->firstOrFail();
        $guide = Guides::where('activity_organizer_id', $organizer->id)->findOrFail($id);

        $guide->delete();

        return redirect()->route('guides.index')
            ->with('success', 'تم حذف المرشد بنجاح');
    }
}
