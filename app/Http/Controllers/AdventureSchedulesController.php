<?php

namespace App\Http\Controllers;

use App\Models\Adventure;
use App\Models\AdventureDay;
use App\Models\Adventure_schedules;
use App\Models\Guides;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdventureSchedulesController extends Controller
{
    /**
     * Display schedules, optionally filtered by adventure.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'adventure_id' => ['nullable', 'integer', 'exists:adventures,id'],
        ]);

        $schedules = Adventure_schedules::with(['adventure', 'guide'])
            ->when(
                filled($validated['adventure_id'] ?? null),
                fn (Builder $query) => $query->where('adventure_id', $validated['adventure_id'])
            )
            ->latest('starts_at')
            ->paginate(15)
            ->withQueryString();
        $adventures = Adventure::orderBy('title')->get();

        return view('adventuresSchedules.index', compact('schedules', 'adventures'));
    }

    /**
     * Show either phase of schedule creation.
     */
    public function create(Request $request)
    {
        $schedule = null;
        $dayCount = 0;

        if ($request->query('phase') === 'days') {
            abort_unless($request->filled('schedule_id'), 404);

            $schedule = Adventure_schedules::with(['adventure.days'])
                ->findOrFail($request->query('schedule_id'));
            $dayCount = $schedule->starts_at->copy()->startOfDay()
                ->diffInDays($schedule->ends_at->copy()->startOfDay()) + 1;
        }

        $adventures = $schedule ? collect() : Adventure::orderBy('title')->get();
        $guides = Guides::all();

        return view('adventuresSchedules.create', compact('schedule', 'adventures', 'dayCount', 'guides'));
    }

    /**
     * Store a schedule or the itinerary days in phase two.
     */
    public function store(Request $request)
    {
        if ($request->input('phase') === 'days') {
            return $this->storeDays($request);
        }

        $validated = $request->validate([
            'adventure_id' => ['required', 'integer', 'exists:adventures,id'],
            'guide_id' => ['required', 'integer', 'exists:guides,id'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'capacity' => ['required', 'integer', 'min:1'],
        ]);

        $schedule = Adventure_schedules::create($validated);

        if ($request->input('_form_type') === 'optional') {
            return redirect()->route('adventures.index', [
                'phase' => 'optional',
                'adventure_id' => $schedule->adventure_id,
            ])->with('success', 'Schedule created successfully');
        }

        if ($request->input('_form_type') === 'edit') {
            return redirect()->route('adventures.edit', $schedule->adventure_id)
                ->with('success', 'Schedule created successfully');
        }

        return redirect()->route('adventure-schedules.create', [
            'phase' => 'days',
            'schedule_id' => $schedule->id,
        ])->with('success', 'Schedule created. Add the adventure itinerary.');
    }

    /**
     * Display a schedule and its adventure itinerary.
     */
    public function show(Adventure_schedules $adventure_schedules)
    {
        $adventure_schedules->load(['adventure.days', 'guide']);

        return view('adventuresSchedules.show', ['schedule' => $adventure_schedules]);
    }

    /**
     * Update an itinerary day belonging to the schedule's adventure.
     */
    public function updateDay(
        Request $request,
        Adventure_schedules $adventure_schedules,
        AdventureDay $adventure_day
    ) {
        abort_unless(
            (int) $adventure_day->adventure_id === (int) $adventure_schedules->adventure_id,
            404
        );

        $validated = $request->validate([
            'day_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('adventure_days', 'day_number')
                    ->where(fn (Builder $query) => $query->where('adventure_id', $adventure_schedules->adventure_id))
                    ->ignore($adventure_day->id),
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $adventure_day->update($validated);

        return redirect()->route('adventure-schedules.show', $adventure_schedules)
            ->with('success', 'Adventure day updated successfully');
    }

    /**
     * Show the schedule edit form.
     */
    public function edit(Adventure_schedules $adventure_schedules)
    {
        $adventure_schedules->load(['adventure.days', 'guide.activityOrganizer.user']);
        $adventures = Adventure::orderBy('title')->get();
        $guides = Guides::with('activityOrganizer.user')->orderBy('id')->get();

        return view('adventuresSchedules.edit', compact('adventure_schedules', 'adventures', 'guides'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adventure_schedules $adventure_schedules)
    {
        $validated = $request->validate([
            'adventure_id' => ['required', 'integer', 'exists:adventures,id'],
            'guide_id' => ['required', 'integer', 'exists:guides,id'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'capacity' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['Active', 'Suspended'])],
        ]);

        if ($adventure_schedules->adventure_id === (int) $validated['adventure_id']) {
            $dayRules = [
                'days' => ['sometimes', 'array'],
                'days.*.day_number' => ['required', 'integer', 'min:1'],
                'days.*.title' => ['required', 'string', 'max:255'],
                'days.*.description' => ['nullable', 'string'],
            ];
            $validatedDays = $request->validate($dayRules)['days'] ?? [];
            $numbers = array_column($validatedDays, 'day_number');
            if (count($numbers) !== count(array_unique($numbers))) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'days' => 'Each adventure day must have a different day number.',
                ]);
            }
            foreach ($validatedDays as $dayId => $dayData) {
                $day = AdventureDay::where('adventure_id', $adventure_schedules->adventure_id)
                    ->findOrFail($dayId);
                $conflict = AdventureDay::where('adventure_id', $adventure_schedules->adventure_id)
                    ->where('day_number', $dayData['day_number'])
                    ->where('id', '!=', $day->id)
                    ->exists();
                if ($conflict) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        "days.$dayId.day_number" => 'This day number is already in use for the adventure.',
                    ]);
                }
                $day->update($dayData);
            }
        }

        $adventure_schedules->update($validated);

        if ($request->routeIs('adventure-schedules.update')) {
            return redirect()->route('adventure-schedules.show', $adventure_schedules)
                ->with('success', 'Schedule updated successfully');
        }

        return redirect()->route('adventures.edit', $adventure_schedules->adventure_id)
            ->with('success', 'Schedule updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Adventure_schedules $adventure_schedules)
    {
        $adventure_schedules->delete();

        return redirect()->route('adventure-schedules.index')
            ->with('success', 'Schedule deleted successfully');
    }

    private function storeDays(Request $request)
    {
        $validatedSchedule = $request->validate([
            'schedule_id' => ['required', 'integer', 'exists:adventure_schedules,id'],
        ]);
        $schedule = Adventure_schedules::findOrFail($validatedSchedule['schedule_id']);

        $expectedDayCount = $schedule->starts_at->copy()->startOfDay()
            ->diffInDays($schedule->ends_at->copy()->startOfDay()) + 1;

        $rules = ['days' => ['required', 'array']];
        for ($index = 0; $index < $expectedDayCount; $index++) {
            $rules["days.{$index}.title"] = ['required', 'string', 'max:255'];
            $rules["days.{$index}.description"] = ['nullable', 'string'];
        }
        $validated = $request->validate($rules);

        DB::transaction(function () use ($validated, $schedule, $expectedDayCount) {
            for ($index = 0; $index < $expectedDayCount; $index++) {
                $day = $validated['days'][$index];
                AdventureDay::updateOrCreate(
                    ['adventure_id' => $schedule->adventure_id, 'day_number' => $index + 1],
                    ['title' => $day['title'], 'description' => $day['description'] ?? null]
                );
            }
        });

        return redirect()->route('adventure-schedules.show', $schedule)
            ->with('success', 'Adventure itinerary added successfully');
    }
}
