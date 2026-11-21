<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Payment::class);

        $query = Payment::query()->latest('id');
        $user = $request->user();

        if (! $user->isAdmin()) {
            $query->whereHas('booking.adventurer', fn ($adventurers) => $adventurers->where('user_id', $user->id));
        }

        return view('payments.index', ['payments' => $query->get()]);
    }

    public function create(): RedirectResponse
    {
        Gate::authorize('viewAny', Payment::class);

        return redirect()->route('payments.index');
    }

    public function store(): void
    {
        Gate::authorize('create', Payment::class);
    }

    public function show(Payment $payment): View
    {
        Gate::authorize('viewAny', Payment::class);
        Gate::authorize('view', $payment);

        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment): void
    {
        Gate::authorize('update', $payment);
    }

    public function update(Payment $payment): void
    {
        Gate::authorize('update', $payment);
    }

    public function destroy(Payment $payment): void
    {
        Gate::authorize('delete', $payment);
    }

    public function showByBooking(string $bookingId): View
    {
        Gate::authorize('viewAny', Payment::class);

        $booking = Booking::findOrFail($bookingId);
        Gate::authorize('viewForBooking', [Payment::class, $booking]);

        $payments = Payment::where('booking_id', $booking->id)->latest('id')->get();

        return view('payments.index', compact('payments'));
    }
}
