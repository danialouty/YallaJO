<?php

namespace App\Http\Controllers;

use App\Models\ActivityOrganizer;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminOrganizerController extends Controller
{
    public function index(): View
    {
        $organizers = ActivityOrganizer::with('user')
            ->latest()
            ->get();

        // Platform revenue & activity metrics
        $totalRevenue = Payment::where('status', 'Confirmed')->sum('total_amount');        $totalBookings = Booking::count();
        $pendingCount = $organizers->where('approval_status', 'Pending')->count();

        return view('admin.organizers', compact(
            'organizers',
            'totalRevenue',
            'totalBookings',
            'pendingCount'
        ));
    }

    public function update(Request $request, ActivityOrganizer $organizer): RedirectResponse
    {
        $validated = $request->validate([
            'approval_status' => ['required', 'in:Approved,Rejected'],
        ]);

        $isApproved = $validated['approval_status'] === 'Approved';

        $organizer->update([
            'approval_status' => $validated['approval_status'],
            'approved_by'     => $isApproved ? $request->user()->id : null,
            'approved_at'     => $isApproved ? now() : null,
        ]);

        return redirect()
            ->route('admin.organizers.index')
            ->with('status', "Organizer {$organizer->user->name} marked as {$validated['approval_status']}.");
    }

    public function destroy(ActivityOrganizer $organizer): RedirectResponse
    {
        $name = $organizer->user->name;
        $organizer->delete();

        return redirect()
            ->route('admin.organizers.index')
            ->with('status', "Organizer profile for {$name} deleted.");
    }
}
