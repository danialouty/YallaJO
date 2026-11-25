<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class AdminFraudReportController extends Controller
{
    private const REPORT_STATUSES = ['pending' => 'بانتظار المراجعة', 'resolved' => 'تمّت المعالجة', 'dismissed' => 'تمّ التجاهل'];

    public function index(Request $request): View|RedirectResponse
    {
        $validator = Validator::make($request->query(), [
            'search'        => ['nullable', 'string', 'max:100'],
            'report_status' => ['nullable', 'in:pending,resolved,dismissed'],
        ], [
            'search.*'              => 'أدخل عبارة بحث نصية صالحة.',
            'report_status.in'      => 'اختر حالة بلاغ صالحة.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.reports.index')->withErrors($validator);
        }

        $filters = $validator->validated();

        $query = Review::with(['adventurer.user', 'adventure', 'booking'])
            ->where('is_reported', true)
            ->orderByRaw("CASE report_status WHEN 'pending' THEN 1 WHEN 'resolved' THEN 2 WHEN 'dismissed' THEN 3 ELSE 0 END")
            ->latest();

        if (filled($filters['search'] ?? null)) {
            $pattern = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($pattern) {
                $q->whereLike('report_reason', $pattern)
                  ->orWhereLike('comment', $pattern)
                  ->orWhereHas('adventure', fn ($a) => $a->whereLike('title', $pattern))
                  ->orWhereHas('adventurer.user', fn ($u) => $u->whereLike('name', $pattern));
            });
        }

        if (filled($filters['report_status'] ?? null)) {
            $query->where('report_status', $filters['report_status']);
        }

        $flaggedReviews = $query->paginate(15)->appends($filters);

        return view('admin.reports.index', [
            'flaggedReviews'  => $flaggedReviews,
            'filters'         => $filters,
            'reportStatuses'  => self::REPORT_STATUSES,
        ]);
    }

    public function suspendAdventure(Request $request, Review $review): RedirectResponse
    {
        $request->validate(
            ['current_password' => ['required', 'current_password']],
            ['current_password.required' => 'يجب تأكيد كلمة مرورك لإتمام هذا الإجراء.',
             'current_password.current_password' => 'كلمة المرور غير صحيحة.'],
        );

        DB::transaction(function () use ($review) {
            $review->adventure()->update(['status' => 'Suspended']);
            $review->update(['report_status' => 'resolved']);
        });

        return back()->with('status', 'تم إيقاف المغامرة المشبوهة (Suspended) وإغلاق البلاغ.');
    }

    public function dismissReport(Request $request, Review $review): RedirectResponse
    {
        $request->validate(
            ['current_password' => ['required', 'current_password']],
            ['current_password.required' => 'يجب تأكيد كلمة مرورك لإتمام هذا الإجراء.',
             'current_password.current_password' => 'كلمة المرور غير صحيحة.'],
        );

        $review->update([
            'is_reported'   => false,
            'report_status' => 'dismissed',
        ]);

        return back()->with('status', 'تم تجاهل البلاغ وإعادة التقييم لوضعه الطبيعي.');
    }
}
