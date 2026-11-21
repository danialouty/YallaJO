<?php

namespace App\Http\Controllers;

use App\Models\Adventure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class AdventureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        if (! $user?->isApprovedOrganizer()) {
            return redirect()->route('adventuresAll.index');
        }

        $owned = $user->activityOrganizer->adventures();
        $adventures = (clone $owned)->with(['images', 'schedules'])->latest()->paginate(5);
        $optionalAdventure = request('phase') === 'optional'
            ? $owned->with(['images', 'schedules'])->find(request()->integer('adventure_id'))
            : null;

        return view('adventures.index', compact('adventures', 'optionalAdventure'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('adventures.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $adventure = $request->user()->activityOrganizer->adventures()
            ->create($request->validate($this->rules()));

        return redirect()->route('adventures.index', [
            'phase' => 'optional',
            'adventure_id' => $adventure->id,
        ])
            ->with('success', 'Adventure created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Adventure $adventure)
    {
        Gate::authorize('manage', $adventure);
        $adventure->load(['images', 'schedules']);
        return view('adventures.show', compact('adventure'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Adventure $adventure)
    {
        Gate::authorize('manage', $adventure);
        $adventure->load(['images', 'schedules']);

        return view('adventures.edit', compact('adventure'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adventure $adventure)
    {
        Gate::authorize('manage', $adventure);
        $validated = $request->validate($this->rules());

        DB::transaction(function () use ($adventure, $validated) {
            $current = Adventure::query()->lockForUpdate()->findOrFail($adventure->id);
            if ($validated['capacity'] < $current->capacity
                && $validated['capacity'] < $current->reservedBookings()->sum('participants_count')) {
                throw ValidationException::withMessages(['capacity' => 'السعة أقل من المقاعد المحجوزة.']);
            }
            $current->update($validated);
        });

        return redirect()->route('adventures.index')
            ->with('success', 'Adventure updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Adventure $adventure)
    {
        Gate::authorize('manage', $adventure);

        DB::transaction(function () use ($adventure) {
            $current = Adventure::query()->lockForUpdate()->findOrFail($adventure->id);
            if ($current->bookings()->exists() || $current->reviews()->exists() || $current->images()->exists()) {
                throw ValidationException::withMessages(['adventure' => 'علّق المغامرة للإبقاء على الحجوزات والتقييمات والصور المرتبطة بها.']);
            }
            $current->delete();
        });

        return redirect()->route('adventures.index')
            ->with('success', 'Adventure deleted successfully');
    }

    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'difficulty_level' => ['required', 'in:Easy,Medium,Hard'],
            'capacity' => ['required', 'integer', 'between:1,2147483647'],
            'category' => ['required', 'in:Hiking,Diving,Camping,Cyciling,Zaitoon day out,kayaking,climbing,Sky Diving,Blimping,Toursim'],
            'location' => ['required', 'string', 'max:255'],
            'duration_hours' => ['required', 'numeric', 'decimal:0,2', 'gt:0', 'max:999.99'],
            'price' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:9999999.99'],
            'requirements' => ['required', 'string', 'max:10000'],
            'status' => ['required', 'in:Active,Suspended'],
        ];
    }

    public function indexAll(Request $request)
    {
        $query = Adventure::where('status', 'Active');

        // Search by adventure name
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $adventures = $query->latest()->paginate(12)->withQueryString();

        $locations = Adventure::select('location')
            ->distinct()
            ->pluck('location');

        $categories = Adventure::select('category')
            ->distinct()
            ->pluck('category');

        return view('adventures', compact(
            'adventures',
            'locations',
            'categories'
        ));
    }

    public function showProfile(Adventure $adventure)
    {
        $adventure->load('images');

        return view('adventureProfile', compact('adventure'));
    }
}
