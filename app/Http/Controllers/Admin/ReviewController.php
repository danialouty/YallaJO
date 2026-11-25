<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $validator = Validator::make($request->query(), [
            'search' => ['nullable', 'string', 'max:100'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'is_reported' => ['nullable', 'boolean'],
        ], [
            'search.*' => 'أدخل قيمة بحث صالحة.',
            'rating.*' => 'اختر تقييمًا من ١ إلى ٥.',
            'is_reported.*' => 'اختر قيمة صالحة لحقل البلاغ.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.reviews.index')->withErrors($validator);
        }

        $filters = $validator->validated();
        $query = Review::with(['adventure:id,title', 'adventurer.user:id,name'])->latest('id');

        if (filled($filters['search'] ?? null)) {
            $search = '%'.$filters['search'].'%';
            $query->where(fn ($reviews) => $reviews->whereLike('comment', $search)
                ->orWhereHas('adventure', fn ($adventures) => $adventures->whereLike('title', $search)));
        }

        if (filled($filters['rating'] ?? null)) {
            $query->where('rating', (int) $filters['rating']);
        }

        if (isset($filters['is_reported'])) {
            $query->where('is_reported', (bool) $filters['is_reported']);
        }

        return view('admin.reviews.index', [
            'records' => $query->paginate(15)->appends($filters),
            'filters' => $filters,
        ]);
    }

    public function show(string $review): View
    {
        $review = Review::with(['adventure:id,title', 'adventurer.user:id,name'])->findOrFail($review);

        return view('admin.reviews.show', compact('review'));
    }

    public function destroy(Request $request, string $review): RedirectResponse
    {
        $request->validate(
            [
                'confirm_delete' => ['accepted'],
                'current_password' => ['required', 'current_password'],
            ],
            [
                'confirm_delete.accepted' => 'يجب تأكيد رغبتك في الحذف.',
                'current_password.required' => 'يجب تأكيد كلمة مرورك لإتمام الحذف.',
                'current_password.current_password' => 'كلمة المرور غير صحيحة.',
            ],
        );
        Review::findOrFail($review)->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'حُذف التقييم.');
    }
}
