@extends('layouts.site')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-10" dir="rtl">
    <h1 class="mb-2 font-display text-2xl font-bold text-ink">تعديل التقييم</h1>
    <p class="mb-6 text-sm text-muted">المغامرة: {{ $adventure->title }}</p>

    <form method="POST" action="{{ route('reviews.update', [$adventure, $review]) }}"
          class="space-y-4 rounded-2xl border border-black/10 bg-white p-6 shadow-sm">
        @csrf
        @method('PATCH')

        <div>
            <label class="mb-1 block text-sm font-medium text-ink">التقييم</label>
            <select name="rating" required class="w-full rounded-control border border-black/15 px-3 py-2 text-sm">
                @for ($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" @selected($review->rating == $i)>{{ $i }} / 5</option>
                @endfor
            </select>
            @error('rating') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-ink">التعليق</label>
            <textarea name="comment" rows="4" required
                      class="w-full rounded-control border border-black/15 px-3 py-2 text-sm">{{ old('comment', $review->comment) }}</textarea>
            @error('comment') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('reviews.index', $adventure) }}"
               class="rounded-control border border-black/15 px-4 py-2 text-sm text-ink">إلغاء</a>
            <button type="submit"
                    class="rounded-control bg-forest px-5 py-2 text-sm font-semibold text-white hover:opacity-90">
                حفظ التعديلات
            </button>
        </div>
    </form>
</div>
@endsection