@php
    $modalName = $modalName ?? null;
    $fieldPrefix = $modalName ?? 'edit-review-page';
    $errorBag = $errors->getBag($modalName ?? 'default');
    $restoreModalInput = $modalName && old('_modal') === $modalName && $errorBag->any();
    $restoreInput = !$modalName || $restoreModalInput;
    $selectedRating = $restoreInput ? old('rating', $review->rating) : $review->rating;
    $comment = $restoreInput ? old('comment', $review->comment) : $review->comment;
    $ratingError = $restoreInput ? $errorBag->first('rating') : '';
    $commentError = $restoreInput ? $errorBag->first('comment') : '';
@endphp

<form action="{{ route('reviews.update', ['adventure' => $adventure, 'review' => $review]) }}" method="POST" class="space-y-6">
    @csrf
    @method('PATCH')

    @if ($modalName)
        <input type="hidden" name="_modal" value="{{ $modalName }}">
        <input type="hidden" name="_error_bag" value="{{ $modalName }}">
    @endif

    @if ($restoreModalInput)
        <p tabindex="-1" autofocus class="rounded-control bg-danger/5 px-4 py-3 font-semibold text-danger">
            يرجى مراجعة الحقول الموضّحة أدناه.
        </p>
    @endif

    <fieldset class="space-y-2" @if ($ratingError) aria-describedby="{{ $fieldPrefix }}-rating-error" @endif>
        <legend class="text-sm font-semibold text-ink">التقييم من ٥ نجوم</legend>
        <div class="grid max-w-sm grid-cols-5 gap-1.5 sm:gap-2">
            @for ($rating = 1; $rating <= 5; $rating++)
                <label class="relative cursor-pointer">
                    <input
                        id="{{ $fieldPrefix }}-rating-{{ $rating }}"
                        type="radio" name="rating" value="{{ $rating }}" required class="peer sr-only"
                        @checked((is_int($selectedRating) || is_string($selectedRating)) && (string) $selectedRating === (string) $rating)
                    @if ($ratingError) aria-invalid="true" aria-describedby="{{ $fieldPrefix }}-rating-error" @endif
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
        @if ($ratingError)
            <p id="{{ $fieldPrefix }}-rating-error" class="text-sm text-danger" role="alert">{{ $ratingError }}</p>
        @endif
    </fieldset>

    <div class="space-y-2">
        <label for="{{ $fieldPrefix }}-comment" class="block text-sm font-semibold text-ink">التعليق</label>
        <textarea
            id="{{ $fieldPrefix }}-comment" name="comment" rows="5" maxlength="1000" required dir="auto"
            aria-describedby="{{ $fieldPrefix }}-comment-hint{{ $commentError ? ' ' . $fieldPrefix . '-comment-error' : '' }}"
            @if ($commentError) aria-invalid="true" @endif
            class="block w-full rounded-control border border-outline/60 bg-surface px-4 py-3
                   text-sm text-ink focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20"
        >{{ is_string($comment) ? $comment : '' }}</textarea>
        <p id="{{ $fieldPrefix }}-comment-hint" class="text-sm text-muted">الحد الأقصى ١٠٠٠ حرف.</p>
        @if ($commentError)
            <p id="{{ $fieldPrefix }}-comment-error" class="text-sm text-danger" role="alert">{{ $commentError }}</p>
        @endif
    </div>

    <div class="flex flex-col gap-3 sm:flex-row">
        <x-ui.button type="submit" size="lg">حفظ التعديلات</x-ui.button>
        @if ($modalName)
            <x-ui.button type="submit" variant="outline" size="lg" formmethod="dialog" formnovalidate>
                إلغاء
            </x-ui.button>
        @else
            <a
                href="{{ route('reviews.show', ['adventure' => $adventure, 'review' => $review]) }}"
                class="inline-flex min-h-12 items-center justify-center rounded-control border border-outline
                       px-6 py-3 font-semibold text-ink hover:bg-brand/5 focus-visible:outline-2
                       focus-visible:outline-offset-2 focus-visible:outline-brand"
            >
                إلغاء
            </a>
        @endif
    </div>
</form>
