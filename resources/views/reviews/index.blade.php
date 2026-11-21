@extends('layouts.site')

@section('title', 'تقييمات ' . $adventure->title . ' | YallaJO')

@section('content')
    @php
        $reviewCount = $reviews->count();
        $averageRating = $reviews->avg('rating');
    @endphp

    <section class="bg-canvas py-8 sm:py-14" aria-labelledby="reviews-heading">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <a
                href="{{ route('adventures.profile', ['adventure' => $adventure]) }}"
                class="mb-6 inline-flex min-h-11 items-center gap-2 rounded-control text-sm
                       font-semibold text-brand hover:underline focus-visible:outline-2
                       focus-visible:outline-offset-4 focus-visible:outline-brand"
            >
                <span aria-hidden="true">→</span>
                العودة إلى المغامرة
            </a>

            <header class="mb-8 grid gap-5 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-end">
                <div class="min-w-0">
                    <h1 id="reviews-heading" class="font-display text-3xl font-bold text-ink sm:text-4xl">
                        التقييمات
                    </h1>
                    <p class="mt-2 text-lg text-muted [overflow-wrap:anywhere]">{{ $adventure->title }}</p>
                </div>

                @if ($reviewCount > 0)
                    <x-ui.card variant="soft" padding="sm">
                        <dl class="flex flex-wrap gap-x-8 gap-y-3">
                            @if ($averageRating !== null)
                                <div>
                                    <dt class="text-sm text-muted">متوسط التقييم</dt>
                                    <dd class="mt-1 font-semibold text-ink">
                                        {{ number_format($averageRating, 1) }} من 5
                                    </dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-sm text-muted">عدد التقييمات</dt>
                                <dd class="mt-1 font-semibold text-ink">{{ $reviewCount }}</dd>
                            </div>
                        </dl>
                    </x-ui.card>
                @endif
            </header>

            @if (session('success'))
                <div role="status" class="mb-6 rounded-control border border-forest/30 bg-forest/10 p-4 text-sm text-forest">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div role="alert" class="mb-6 rounded-control border border-danger/30 bg-danger/5 p-4 text-sm text-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if ($reviews->isEmpty())
                <x-ui.card variant="outline" padding="lg" class="text-center">
                    <p class="font-semibold text-ink">لا توجد تقييمات لهذه المغامرة بعد.</p>
                </x-ui.card>
            @else
                <ul role="list" class="grid gap-4">
                    @foreach ($reviews as $review)
                        <li class="min-w-0">
                            @include('reviews.partials.review-card', ['review' => $review])

                            <a
                                href="{{ route('reviews.show', ['adventure' => $adventure, 'review' => $review]) }}"
                                class="mt-1 inline-flex min-h-11 items-center rounded-control px-2 text-sm font-semibold
                                       text-brand underline underline-offset-4 hover:text-brand-dark focus-visible:outline-2
                                       focus-visible:outline-offset-2 focus-visible:outline-brand"
                            >
                                عرض تفاصيل التقييم<span class="sr-only"> رقم {{ $review->getKey() }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>
@endsection
