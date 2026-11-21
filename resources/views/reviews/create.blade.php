@extends('layouts.site')

@section('title', 'إضافة تقييم | ' . $adventure->title . ' | YallaJO')

@section('content')
    <section class="bg-canvas py-8 sm:py-14" aria-labelledby="create-review-heading">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <a
                href="{{ route('reviews.index', ['adventure' => $adventure]) }}"
                class="mb-6 inline-flex min-h-11 items-center gap-2 rounded-control text-sm font-semibold
                       text-brand hover:underline focus-visible:outline-2 focus-visible:outline-offset-4
                       focus-visible:outline-brand"
            >
                <span aria-hidden="true">→</span>
                العودة إلى التقييمات
            </a>

            <header class="mb-6">
                <h1 id="create-review-heading" class="font-display text-3xl font-bold text-ink sm:text-4xl">
                    إضافة تقييم
                </h1>
                <p class="mt-2 text-lg text-muted [overflow-wrap:anywhere]">{{ $adventure->title }}</p>
            </header>

            <x-ui.card variant="outline" padding="lg">
                @if ($bookings->isEmpty())
                    <p class="text-ink">لا توجد لديك حجوزات متاحة للتقييم لهذه المغامرة.</p>
                @else
                    @php
                        $oldBooking = old('booking_id');
                        $oldRating = old('rating');
                        $oldComment = old('comment');
                    @endphp

                    <form action="{{ route('reviews.store', ['adventure' => $adventure]) }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="space-y-2">
                            <label for="review-booking" class="block text-sm font-semibold text-ink">الحجز</label>
                            <select
                                id="review-booking" name="booking_id" required
                                @error('booking_id') aria-invalid="true" aria-describedby="review-booking-error" @enderror
                                class="block w-full rounded-control border border-outline/60 bg-surface py-3 ps-4 pe-10
                                       text-sm text-ink rtl:bg-position-[left_0.5rem_center]
                                       focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20"
                            >
                                <option value="">اختر الحجز</option>
                                @foreach ($bookings as $booking)
                                    <option value="{{ $booking->id }}" @selected(is_scalar($oldBooking) && (string) $oldBooking === (string) $booking->id)>
                                        الحجز رقم {{ $booking->id }}
                                    </option>
                                @endforeach
                            </select>
                            @error('booking_id')
                                <p id="review-booking-error" class="text-sm text-danger" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <fieldset class="space-y-2" @error('rating') aria-describedby="review-rating-error" @enderror>
                            <legend class="text-sm font-semibold text-ink">التقييم من ٥ نجوم</legend>
                            <div class="grid max-w-sm grid-cols-5 gap-1.5 sm:gap-2">
                                @for ($rating = 1; $rating <= 5; $rating++)
                                    <label class="relative cursor-pointer">
                                        <input
                                            type="radio" name="rating" value="{{ $rating }}" required class="peer sr-only"
                                            @checked(is_scalar($oldRating) && (string) $oldRating === (string) $rating)
                                            @error('rating') aria-invalid="true" @enderror
                                        >
                                        <span class="sr-only">{{ $rating }} من ٥ نجوم</span>
                                        <span
                                            aria-hidden="true"
                                            class="flex min-h-12 flex-col items-center justify-center rounded-control
                                                   border border-outline/60 bg-surface py-1 text-sm text-ink
                                                   hover:border-brand peer-checked:border-brand peer-checked:bg-brand/10
                                                   peer-checked:font-bold peer-checked:text-brand
                                                   peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2
                                                   peer-focus-visible:outline-brand"
                                        >
                                            <span class="text-xl leading-none">★</span>
                                            {{ $rating }}
                                        </span>
                                    </label>
                                @endfor
                            </div>
                            @error('rating')
                                <p id="review-rating-error" class="text-sm text-danger" role="alert">{{ $message }}</p>
                            @enderror
                        </fieldset>

                        <div class="space-y-2">
                            <label for="review-comment" class="block text-sm font-semibold text-ink">التعليق</label>
                            <textarea
                                id="review-comment" name="comment" rows="5" maxlength="1000" required dir="auto"
                                aria-describedby="review-comment-hint{{ $errors->has('comment') ? ' review-comment-error' : '' }}"
                                @error('comment') aria-invalid="true" @enderror
                                class="block w-full rounded-control border border-outline/60 bg-surface px-4 py-3
                                       text-sm text-ink focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20"
                            >{{ is_string($oldComment) ? $oldComment : '' }}</textarea>
                            <p id="review-comment-hint" class="text-sm text-muted">الحد الأقصى ١٠٠٠ حرف.</p>
                            @error('comment')
                                <p id="review-comment-error" class="text-sm text-danger" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <x-ui.button type="submit" size="lg" class="w-full sm:w-auto">إرسال التقييم</x-ui.button>
                    </form>
                @endif
            </x-ui.card>
        </div>
    </section>
@endsection
