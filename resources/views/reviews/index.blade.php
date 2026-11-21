@extends('layouts.site')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-10" dir="rtl">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-xs text-muted">تقييمات المغامرة</p>
            <h1 class="font-display text-2xl font-bold text-ink">{{ $adventure->title }}</h1>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('adventures.profile', $adventure) }}"
               class="rounded-control border border-black/15 px-4 py-2 text-sm text-ink hover:bg-black/5">
                العودة للمغامرة
            </a>
            @auth
                @if (auth()->user()->role === 'Adventurer')
                    <a href="{{ route('reviews.create', $adventure) }}"
                       class="rounded-control bg-brand px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                        + أضف تقييمك
                    </a>
                @endif
            @endauth
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-control border border-emerald-500/30 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-control border border-rose-500/30 bg-rose-50 px-4 py-3 text-sm text-rose-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse ($reviews as $review)
            <div class="rounded-2xl border border-black/10 bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-ink">
                                {{ $review->adventurer->user->name ?? 'مغامر' }}
                            </span>
                            <span class="text-sm text-amber-500">
                                {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                            </span>
                        </div>
                        <p class="mt-2 text-sm text-ink/85">{{ $review->comment }}</p>
                        <p class="mt-2 text-xs text-muted">{{ $review->created_at->diffForHumans() }}</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('reviews.show', [$adventure, $review]) }}"
                           class="rounded-control border border-black/15 px-3 py-1.5 text-xs text-ink hover:bg-black/5">
                            التفاصيل
                        </a>

                        @auth
                            @if (auth()->user()->adventurer && auth()->user()->adventurer->id === $review->adventurer_id)
                                <a href="{{ route('reviews.edit', [$adventure, $review]) }}"
                                   class="rounded-control bg-forest px-3 py-1.5 text-xs text-white hover:opacity-90">
                                    تعديل
                                </a>

                                <form method="POST" action="{{ route('reviews.destroy', [$adventure, $review]) }}"
                                      onsubmit="return confirm('هل تريد حذف تقييمك؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="rounded-control bg-rose-600 px-3 py-1.5 text-xs text-white hover:bg-rose-700">
                                        حذف
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>

                {{-- قسم الإبلاغ عن احتيال أو مخالفة --}}
                @auth
                    <div class="mt-4 border-t border-black/10 pt-3">
                        @if ($review->is_reported)
                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-800">
                                تم الإبلاغ عن هذا التقييم/المغامرة (الحالة: {{ $review->report_status }})
                            </span>
                        @else
                            <form method="POST" action="{{ route('reviews.report', [$adventure, $review]) }}"
                                  class="flex flex-wrap items-center gap-2">
                                @csrf
                                <input type="text" name="report_reason" required
                                       placeholder="الإبلاغ عن احتيال أو محتوى مخالف (اكتب السبب)..."
                                       class="min-w-[240px] flex-1 rounded-control border border-black/15 px-3 py-1.5 text-xs">
                                <button type="submit"
                                        class="rounded-control bg-amber-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-amber-700">
                                    إبلاغ الإدارة
                                </button>
                            </form>
                        @endif
                    </div>
                @endauth
            </div>
        @empty
            <div class="rounded-2xl border border-black/10 bg-white p-8 text-center text-sm text-muted">
                لا توجد تقييمات لهذه المغامرة حتى الآن.
            </div>
        @endforelse
    </div>
</div>
@endsection