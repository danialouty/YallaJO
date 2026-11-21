<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminFraudReportController extends Controller
{
    public function index(): View
    {
        $flaggedReviews = Review::with(['adventurer.user', 'adventure', 'booking'])
            ->where('is_reported', true)
            ->orderByRaw("CASE report_status WHEN 'pending' THEN 1 WHEN 'resolved' THEN 2 WHEN 'dismissed' THEN 3 ELSE 0 END")
            ->latest()
            ->get();

        return view('admin.reports.index', compact('flaggedReviews'));
    }

    public function suspendAdventure(Review $review): RedirectResponse
    {
        DB::transaction(function () use ($review) {
            $review->adventure()->update(['status' => 'Suspended']);
            $review->update(['report_status' => 'resolved']);
        });

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
