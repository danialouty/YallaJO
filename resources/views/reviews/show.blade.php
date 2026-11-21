@extends('layouts.site')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-10" dir="rtl">
    <div class="rounded-2xl border border-black/10 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between border-b border-black/10 pb-4">
            <div>
                <p class="text-xs text-muted">{{ $adventure->title }}</p>
                <h1 class="mt-1 font-display text-xl font-bold text-ink">
                    تقييم {{ $review->adventurer->user->name ?? 'مغامر' }}
                </h1>
            </div>
            <span class="text-lg text-amber-500">{{ str_repeat('★', $review->rating) }}</span>
        </div>

        <p class="mt-4 text-sm leading-relaxed text-ink">{{ $review->comment }}</p>

        <div class="mt-6 flex items-center justify-between border-t border-black/10 pt-4">
            <a href="{{ route('reviews.index', $adventure) }}"
               class="rounded-control border border-black/15 px-4 py-2 text-sm text-ink">
                العودة لجميع التقييمات
            </a>
            <span class="text-xs text-muted">{{ $review->created_at->format('Y-m-d H:i') }}</span>
        </div>
    </div>
</div>
@endsection