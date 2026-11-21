<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminFraudReportController extends Controller
{
    public function index(): View
    {
        $flaggedReviews = Review::with(['adventurer.user', 'adventure', 'booking'])
            ->where('is_reported', true)
            ->orderByRaw("FIELD(report_status, 'pending', 'resolved', 'dismissed')")
            ->latest()
            ->get();

        return view('admin.reports.index', compact('flaggedReviews'));
    }

    public function suspendAdventure(Review $review): RedirectResponse
    {
        if ($review->adventure) {
            $review->adventure->update([
                'status' => 'suspended',
            ]);
        }

        $review->update([
            'report_status' => 'resolved',
        ]);

        return back()->with('status', 'تم إيقاف المغامرة المشبوهة (Suspended) وإغلاق البلاغ.');
    }

    public function dismissReport(Review $review): RedirectResponse
    {
        $review->update([
            'is_reported'   => false,
            'report_status' => 'dismissed',
        ]);

        return back()->with('status', 'تم تجاهل البلاغ وإعادة التقييم لوضعه الطبيعي.');
    }
}