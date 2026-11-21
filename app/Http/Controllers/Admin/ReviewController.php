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
        ], [
            'search.*' => 'أدخل قيمة بحث صالحة.',
            'rating.*' => 'اختر تقييمًا من ١ إلى ٥.',
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

    public function edit(string $review): View
    {
        return view('admin.reviews.form', ['review' => Review::findOrFail($review)]);
    }

    public function update(Request $request, string $review): RedirectResponse
    {
        $record = Review::findOrFail($review);
        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'max:1000'],
            'booking_id' => ['missing'],
            'adventure_id' => ['missing'],
            'adventurer_id' => ['missing'],
        ]);
        $record->update($data);

        return redirect()->route('admin.reviews.show', $record)->with('success', 'حُفظ التقييم.');
    }

    public function destroy(Request $request, string $review): RedirectResponse
    {
        $request->validate(['confirm_delete' => ['accepted']]);
        Review::findOrFail($review)->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'حُذف التقييم.');
    }
}
