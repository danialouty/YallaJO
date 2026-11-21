<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookingValidationRequest;
use App\Models\Adventure;
use App\Models\Adventurer;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isOrganizer = $user->role === 'Activity_Organizer';
        $bookings = $isOrganizer
            ? Booking::whereHas('adventure', fn (Builder $query) => $query->where('activity_organizer_id', $user->activityOrganizer->id))
                ->with(['adventure', 'adventurer.user', 'payment'])->latest()->get()
            : $this->ownedBookings()->with('adventure')->withExists(['payment', 'review'])->latest()->get();
        $adventures = $isOrganizer ? collect() : Adventure::where('status', 'Active')->get();

        return view('bookings.index', compact('bookings', 'adventures', 'isOrganizer') + ['data' => $bookings]);
    }

    public function create()
    {
        return view('bookings.create', ['adventures' => Adventure::where('status', 'Active')->get()]);
    }

    public function store(CreateBookingValidationRequest $request)
    {
        if ($request->user()->role !== 'Adventurer') {
            $destination = $request->user()->role === 'Activity_Organizer'
                ? 'organizer.dashboard'
                : 'dashboard';

            return redirect()->route($destination)
                ->with('error', 'الحجز متاح لحسابات المغامرين فقط.');
        }

        $validated = $request->validated();

        $booking = DB::transaction(function () use ($request, $validated) {
            $adventure = Adventure::query()->lockForUpdate()->findOrFail($validated['adventure_id']);
            if ($adventure->status !== 'Active') {
                throw ValidationException::withMessages(['adventure_id' => 'الحجز غير متاح لهذه المغامرة.']);
            }

            $adventurer = Adventurer::firstOrCreate(['user_id' => $request->user()->id]);
            if ($adventure->bookings()->where('adventurer_id', $adventurer->id)->exists()) {
                throw ValidationException::withMessages(['adventure_id' => 'سبق لك حجز هذه المغامرة.']);
            }

            $participants = (int) $validated['participants_count'];
            $this->validateCapacity($adventure, $participants);
            $booking = $adventurer->bookings()->create([
                'adventure_id' => $adventure->id,
                'participants_count' => $participants,
                'unit_price' => $adventure->price,
                'commission_rate' => '0.10',
                'total_amount' => Booking::calculateTotal($adventure->price, $participants),
                'status' => 'Pending',
                'capacity' => $adventure->capacity,
            ]);

            $booking->payment()->create([
                'total_amount' => $booking->total_amount,
                'method' => 'Cash',
                'status' => 'Pending',
            ]);

            return $booking;
        });

        return redirect()->route('bookings.index')
            ->with('success', 'تم إرسال طلب الحجز بنجاح. يمكنك متابعة حالته من سجل حجوزاتك.');
    }

    public function show(string $booking)
    {
        $record = $this->ownedBookings()->with('adventure')->withExists(['payment', 'review'])->findOrFail($booking);

        return view('bookings.show', ['data' => $record]);
    }

    public function edit(string $booking)
    {
        $record = $this->ownedBookings()->with('adventure')->findOrFail($booking);
        abort_unless($record->isChangeable() && $record->adventure->status === 'Active', 403);

        return view('bookings.edit', ['booking' => $record]);
    }

    public function update(Request $request, string $booking)
    {
        $record = $this->ownedBookings()->findOrFail($booking);
        $validated = $request->validate(['participants_count' => ['required', 'integer', 'between:1,2147483647']]);
        $participants = (int) $validated['participants_count'];

        DB::transaction(function () use ($record, $participants) {
            $adventure = Adventure::query()->lockForUpdate()->findOrFail($record->adventure_id);
            $current = $this->ownedBookings()->lockForUpdate()->findOrFail($record->id);
            abort_unless($current->isChangeable() && $adventure->status === 'Active', 403);
            $this->validateCapacity($adventure, $participants, $current);
            $current->update([
                'participants_count' => $participants,
                'total_amount' => Booking::calculateTotal($current->unit_price, $participants),
            ]);
        });

        return redirect()->route('bookings.index')->with('success', 'تم تحديث الحجز.');
    }

    public function destroy(string $booking)
    {
        DB::transaction(function () use ($booking) {
            $current = $this->ownedBookings()->lockForUpdate()->findOrFail($booking);
            abort_unless($current->isChangeable(), 403);
            $current->update(['status' => 'Cancelled', 'cancelled_at' => now()]);
        });

        return redirect()->route('bookings.index')->with('success', 'أُلغي الحجز وبقي سجله محفوظًا.');
    }

    private function ownedBookings(): Builder
    {
        return Booking::whereHas('adventurer', fn (Builder $query) => $query->where('user_id', auth()->id()));
    }

    private function validateCapacity(Adventure $adventure, int $participants, ?Booking $current = null): void
    {
        $reserved = $adventure->reservedBookings()
            ->when($current, fn (Builder $query) => $query->whereKeyNot($current->id))
            ->sum('participants_count');

        if ($participants > $adventure->capacity - $reserved) {
            throw ValidationException::withMessages(['participants_count' => 'عدد المشاركين يتجاوز المقاعد المتاحة.']);
        }
    }
}
