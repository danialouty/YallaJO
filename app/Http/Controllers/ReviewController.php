<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\Adventure;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Adventure $adventure): View
    {
        $reviews = $adventure->reviews()->latest()->get();
        return view('reviews.index', compact('adventure', 'reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(
        Request $request,
        Adventure $adventure
    ): View|RedirectResponse {
        $user = $request->user();

        abort_unless($user->role === 'Adventurer', 403);

        $adventurer = $user->adventurer;

        if (!$adventurer) {
            return redirect()
                ->route('adventurer.create')
                ->with('error', 'Please create your profile first.');
        }

        $bookings = $adventurer->bookings()
            ->where('adventure_id', $adventure->id)
            ->where('status', 'Accepted')
            ->whereNotIn(
                'id',
                Review::query()->select('booking_id')
            )
            ->get();

        return view('reviews.create', compact('adventure', 'bookings'));
    }


    /**
     * Store a newly created resource in storage.
     */

    public function store(
        Request $request,
        Adventure $adventure
    ): RedirectResponse {
        $user = $request->user();

        abort_unless($user->role === 'Adventurer', 403);

        $adventurer = $user->adventurer;

        if (!$adventurer) {
            return redirect()
                ->route('adventurer.create')
                ->with('error', 'Please create your profile first.');
        }

        $validated = $request->validate([
            'booking_id' => [
                'required',
                'integer',
                'exists:bookings,id',
            ],
            'rating' => [
                'required',
                'integer',
                'between:1,5',
            ],
            'comment' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        try {
            $created = DB::transaction(function () use ($adventurer, $adventure, $validated) {
                $booking = $adventurer->bookings()
                    ->where('adventure_id', $adventure->id)
                    ->lockForUpdate()
                    ->findOrFail($validated['booking_id']);

                abort_unless($booking->status === 'Accepted', 403, 'Only accepted bookings can be reviewed.');

                if ($booking->review()->exists()) {
                    return false;
                }

                $booking->review()->create([
                    'adventure_id' => $booking->adventure_id,
                    'adventurer_id' => $booking->adventurer_id,
                    'rating' => $validated['rating'],
                    'comment' => $validated['comment'],
                ]);

                return true;
            });
        } catch (UniqueConstraintViolationException $exception) {
            if (! Review::where('booking_id', $validated['booking_id'])->exists()) {
                throw $exception;
            }
            $created = false;
        }

        if (! $created) {
            return redirect()
                ->route('reviews.index', [
                    'adventure' => $adventure->id,
                ])
                ->with('error', 'أُضيف تقييم لهذا الحجز من قبل.');
        }

        return redirect()
            ->route('reviews.index', [
                'adventure' => $adventure->id,
            ])
            ->with('success', 'Review created successfully.');
    }


    /**
     * Display the specified resource.
     */

    public function show(
        Adventure $adventure,
        Review $review
    ): View {
        abort_unless(
            $review->adventure_id === $adventure->id,
            404
        );

        return view('reviews.show', compact('adventure', 'review'));
    }


    /**
     * Show the form for editing the specified resource.
     */

    public function edit(
        Request $request,
        Adventure $adventure,
        Review $review
    ): View|RedirectResponse {
        $user = $request->user();

        abort_unless($user->role === 'Adventurer', 403);

        $adventurer = $user->adventurer;

        if (!$adventurer) {
            return redirect()
                ->route('adventurer.create')
                ->with('error', 'Please create your profile first.');
        }

        abort_unless(
            $review->adventure_id === $adventure->id,
            404
        );

        abort_unless(
            $review->adventurer_id === $adventurer->id,
            403
        );

        return view('reviews.edit', compact('adventure', 'review'));
    }


    /**
     * Update the specified resource in storage.
     */

    public function update(
        Request $request,
        Adventure $adventure,
        Review $review
    ): RedirectResponse {
        $user = $request->user();

        abort_unless($user->role === 'Adventurer', 403);

        $adventurer = $user->adventurer;

        if (!$adventurer) {
            return redirect()
                ->route('adventurer.create')
                ->with('error', 'Please create your profile first.');
        }

        abort_unless(
            $review->adventure_id === $adventure->id,
            404
        );

        abort_unless(
            $review->adventurer_id === $adventurer->id,
            403
        );

        $validated = $request->validate([
            'rating' => [
                'required',
                'integer',
                'between:1,5',
            ],
            'comment' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        $review->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()
            ->route('reviews.show', [
                'adventure' => $adventure->id,
                'review' => $review->id,
            ])
            ->with('success', 'Review updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy(
        Request $request,
        Adventure $adventure,
        Review $review
    ): RedirectResponse {
        $user = $request->user();

        abort_unless($user->role === 'Adventurer', 403);

        $adventurer = $user->adventurer;

        if (!$adventurer) {
            return redirect()
                ->route('adventurer.create')
                ->with('error', 'Please create your profile first.');
        }

        abort_unless(
            $review->adventure_id === $adventure->id,
            404
        );

        abort_unless(
            $review->adventurer_id === $adventurer->id,
            403
        );

        $review->delete();

        return redirect()
            ->route('reviews.index', [
                'adventure' => $adventure->id,
            ])
            ->with('success', 'Review deleted successfully.');
    }

    public function report(
    Request $request,
    Adventure $adventure,
    Review $review
): RedirectResponse {
    abort_unless($review->adventure_id === $adventure->id, 404);

    $validated = $request->validate([
        'report_reason' => ['required', 'string', 'max:500'],
    ]);

    $review->update([
        'is_reported'   => true,
        'report_status' => 'pending',
        'report_reason' => $validated['report_reason'],
    ]);

    return back()->with('success', 'تم إرسال البلاغ للإدارة بنجاح.');
}

}
