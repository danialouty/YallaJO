<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityOrganizer;
use App\Models\Adventure;
use App\Models\Adventurer;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $organizers = ActivityOrganizer::query()->toBase()
            ->selectRaw('COUNT(*) AS total, COUNT(CASE WHEN approval_status = ? THEN 1 END) AS pending', [ActivityOrganizer::PENDING])
            ->first();

        $adventures = Adventure::query()->toBase()
            ->selectRaw('COUNT(*) AS total, COUNT(CASE WHEN status = ? THEN 1 END) AS active', ['Active'])
            ->first();

        $bookings = Booking::query()->toBase()
            ->selectRaw(
                'COUNT(*) AS total,
                 COUNT(CASE WHEN status = ? THEN 1 END) AS pending,
                 COUNT(CASE WHEN status = ? THEN 1 END) AS accepted,
                 COUNT(CASE WHEN status = ? THEN 1 END) AS cancelled',
                ['Pending', 'Accepted', 'Cancelled'],
            )->first();

        $payments = Payment::query()->toBase()
            ->selectRaw(
                'COUNT(*) AS total,
                 COUNT(CASE WHEN status = ? THEN 1 END) AS confirmed,
                 COALESCE(SUM(CASE WHEN status = ? THEN total_amount END), 0) AS confirmed_amount',
                ['Confirmed', 'Confirmed'],
            )->first();

        return view('admin.dashboard', [
            'metrics' => [
                'users' => User::count(),
                'adventurers' => Adventurer::count(),
                'organizers' => (int) $organizers->total,
                'pendingOrganizers' => (int) $organizers->pending,
                'adventures' => (int) $adventures->total,
                'activeAdventures' => (int) $adventures->active,
                'bookings' => (int) $bookings->total,
                'pendingBookings' => (int) $bookings->pending,
                'acceptedBookings' => (int) $bookings->accepted,
                'cancelledBookings' => (int) $bookings->cancelled,
                'payments' => (int) $payments->total,
                'confirmedPayments' => (int) $payments->confirmed,
                'confirmedPaymentAmount' => $payments->confirmed_amount,
                'reviews' => Review::count(),
            ],
        ]);
    }
}
