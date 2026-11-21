@php
    $rating = $review->rating;
    $date = $review->created_at;
@endphp

<x-ui.card variant="outline">
    <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-2">
        @if ($rating >= 1 && $rating <= 5)
            <div role="img" aria-label="{{ $rating }} من 5 نجوم" class="inline-flex gap-1 text-xl text-accent">
                @for ($star = 1; $star <= 5; $star++)
                    <span aria-hidden="true">{{ $star <= $rating ? '★' : '☆' }}</span>
                @endfor
            </div>
        @endif

        @if ($date)
            <time datetime="{{ $date->toDateString() }}" class="text-sm text-muted">
                {{ $date->copy()->locale('ar')->translatedFormat('j F Y') }}
            </time>
        @endif
    </div>

    @if (filled($review->comment))
        <p dir="auto" class="mt-3 whitespace-pre-line leading-7 text-ink [overflow-wrap:anywhere]">
            {{ $review->comment }}
        </p>
    @endif
</x-ui.card>
