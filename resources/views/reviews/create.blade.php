@extends('layouts.site')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-10" dir="rtl">
    <h1 class="mb-2 font-display text-2xl font-bold text-ink">إضافة تقييم جديد</h1>
    <p class="mb-6 text-sm text-muted">المغامرة: {{ $adventure->title }}</p>

    @if ($bookings->isEmpty())
        <div class="rounded-2xl border border-amber-300 bg-amber-50 p-6 text-sm text-amber-900">
            لا يوجد لديك حجز مقبول (Accepted) غير مُقيّم لهذه المغامرة حالياً.
            <div class="mt-4">
                <a href="{{ route('reviews.index', $adventure) }}" class="font-bold underline">العودة للتقييمات</a>
            </div>
        </div>
    @else
        <form method="POST" action="{{ route('reviews.store', $adventure) }}"
              class="space-y-4 rounded-2xl border border-black/10 bg-white p-6 shadow-sm">
            @csrf

            <div>
                <label class="mb-1 block text-sm font-medium text-ink">اختر الحجز</label>
                <select name="booking_id" required class="w-full rounded-control border border-black/15 px-3 py-2 text-sm">
                    @foreach ($bookings as $booking)
                        <option value="{{ $booking->id }}">حجز رقم #{{ $booking->id }} — {{ $booking->created_at->format('Y-m-d') }}</option>
                    @endforeach
                </select>
                @error('booking_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-ink">التقييم</label>
                <select name="rating" required class="w-full rounded-control border border-black/15 px-3 py-2 text-sm">
                    <option value="5">⭐⭐⭐⭐⭐ (5 - ممتاز)</option>
                    <option value="4">⭐⭐⭐⭐ (4 - جيد جداً)</option>
                    <option value="3">⭐⭐⭐ (3 - جيد)</option>
                    <option value="2">⭐⭐ (2 - مقبول)</option>
                    <option value="1">⭐ (1 - سيء)</option>
                </select>
                @error('rating') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-ink">التعليق</label>
                <textarea name="comment" rows="4" required placeholder="شاركنا تجربتك بالتفصيل..."
                          class="w-full rounded-control border border-black/15 px-3 py-2 text-sm">{{ old('comment') }}</textarea>
                @error('comment') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('reviews.index', $adventure) }}"
                   class="rounded-control border border-black/15 px-4 py-2 text-sm text-ink">إلغاء</a>
                <button type="submit"
                        class="rounded-control bg-brand px-5 py-2 text-sm font-semibold text-white hover:opacity-90">
                    نشر التقييم
                </button>
            </div>
        </form>
    @endif
</div>
@endsection