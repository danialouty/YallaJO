@extends('layouts.site')

@section('title', 'ملفي الشخصي | YallaJO')

@section('content')
@php
    $user = auth()->user();
    $editModalName = 'edit-adventurer';

    $experienceLabels = [
        'Beginner' => 'مبتدئ',
        'Intermediate' => 'متوسط',
        'Advanced' => 'متقدم',
    ];

    $interestLabels = [
        'Hiking' => 'المشي والمسارات',
        'Camping' => 'التخييم',
        'Diving' => 'الغوص',
        'kayaking' => 'التجديف',
        'climbing' => 'التسلق',
    ];

    $interests = is_array($adventurer->interests) ? $adventurer->interests : [];
@endphp

<section class="min-h-screen bg-canvas pb-16">
    <div class="relative overflow-hidden bg-forest">
        <div class="absolute inset-0 opacity-15" aria-hidden="true">
            <div class="absolute -right-24 -top-24 size-72 rounded-full border-[42px] border-white/30"></div>
            <div class="absolute bottom-0 left-1/4 size-40 rounded-full bg-brand/30 blur-3xl"></div>
        </div>

        <div class="relative mx-auto max-w-[1360px] px-4 py-10 sm:px-6 lg:px-12 lg:py-12">
            <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
                <div class="flex items-center gap-5">
                    <div class="flex size-24 shrink-0 items-center justify-center rounded-full border-4 border-white/80 bg-brand font-display text-4xl font-bold text-white shadow-xl"
                         aria-hidden="true">
                        {{ mb_substr(trim($user->name), 0, 1) }}
                    </div>

                    <div class="text-white">
                        <p class="text-sm font-semibold text-white/75">ملف المغامر</p>
                        <h1 class="mt-1 font-display text-3xl font-bold sm:text-4xl">{{ $user->name }}</h1>
                        <p class="mt-2 text-sm text-white/75" dir="ltr">{{ $user->email }}</p>
                    </div>
                </div>

                <a href="{{ route('adventuresAll.index') }}"
                   class="inline-flex min-h-11 self-start items-center justify-center rounded-control bg-white px-5 py-3 text-sm font-bold text-forest shadow-sm transition hover:bg-canvas sm:self-auto">
                    استكشف مغامرة جديدة
                </a>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-[1360px] px-4 sm:px-6 lg:px-12">
        @if (session('success'))
            <div role="status" class="mt-6 rounded-control border border-forest/30 bg-forest/10 p-4 text-sm font-semibold text-forest">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div role="alert" class="mt-6 rounded-control border border-danger/30 bg-danger/5 p-4 text-sm font-semibold text-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="relative z-10 mt-6 grid grid-cols-2 gap-3 sm:grid-cols-2 lg:grid-cols-4 lg:gap-4">
            <div class="min-h-24 rounded-card border border-outline/30 bg-surface p-4 shadow-card sm:min-h-28 sm:p-5">
                <p class="text-[11px] font-semibold leading-5 text-muted sm:text-xs">إجمالي الحجوزات</p>
                <p class="mt-1 font-display text-2xl font-bold text-ink sm:mt-2 sm:text-3xl">{{ $bookingCount }}</p>
            </div>
            <div class="min-h-24 rounded-card border border-outline/30 bg-surface p-4 shadow-card sm:min-h-28 sm:p-5">
                <p class="text-[11px] font-semibold leading-5 text-muted sm:text-xs">الحجوزات المؤكدة</p>
                <p class="mt-1 font-display text-2xl font-bold text-forest sm:mt-2 sm:text-3xl">{{ $confirmedBookingCount }}</p>
            </div>
            <div class="min-h-24 rounded-card border border-outline/30 bg-surface p-4 shadow-card sm:min-h-28 sm:p-5">
                <p class="text-[11px] font-semibold leading-5 text-muted sm:text-xs">بانتظار الموافقة</p>
                <p class="mt-1 font-display text-2xl font-bold text-accent sm:mt-2 sm:text-3xl">{{ $pendingBookingCount }}</p>
            </div>
            <div class="min-h-24 rounded-card border border-outline/30 bg-surface p-4 shadow-card sm:min-h-28 sm:p-5">
                <p class="text-[11px] font-semibold leading-5 text-muted sm:text-xs">التقييمات المكتوبة</p>
                <p class="mt-1 font-display text-2xl font-bold text-brand sm:mt-2 sm:text-3xl">{{ $reviewCount }}</p>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-[320px_minmax(0,1fr)]">
            <aside class="space-y-6">
                <x-ui.card padding="lg">
                    <div class="flex items-center justify-between">
                        <h2 class="font-display text-xl font-bold text-ink">معلوماتي</h2>
                        <button type="button"
                                data-modal-open="{{ $editModalName }}"
                                class="text-sm font-bold text-brand hover:text-brand-dark">
                            تعديل
                        </button>
                    </div>

                    <dl class="mt-6 space-y-5">
                        <div>
                            <dt class="text-xs text-muted">مستوى الخبرة</dt>
                            <dd class="mt-1 font-bold text-ink">
                                {{ $experienceLabels[$adventurer->experience_level] ?? $adventurer->experience_level }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-muted">المنطقة</dt>
                            <dd class="mt-1 font-bold text-ink">{{ $adventurer->location ?: 'غير محددة' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-muted">عضو منذ</dt>
                            <dd class="mt-1 font-bold text-ink" dir="ltr">
                                {{ $adventurer->created_at?->format('Y/m/d') ?? '—' }}
                            </dd>
                        </div>
                    </dl>
                </x-ui.card>

                <x-ui.card padding="lg">
                    <h2 class="font-display text-xl font-bold text-ink">اهتماماتي</h2>
                    <div class="mt-5 flex flex-wrap gap-2">
                        @forelse ($interests as $interest)
                            <span class="rounded-full border border-forest/20 bg-forest/10 px-3 py-2 text-xs font-bold text-forest">
                                {{ $interestLabels[$interest] ?? $interest }}
                            </span>
                        @empty
                            <p class="text-sm leading-7 text-muted">اختر اهتماماتك لتظهر لك مغامرات أكثر ملاءمة.</p>
                        @endforelse
                    </div>
                </x-ui.card>
            </aside>

            <div class="space-y-6">
                <x-ui.card padding="lg">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold text-brand">آخر النشاطات</p>
                            <h2 class="mt-1 font-display text-xl font-bold text-ink">حجوزاتي الأخيرة</h2>
                        </div>
                        <a href="{{ route('bookings.index') }}" class="text-sm font-bold text-brand hover:text-brand-dark">
                            عرض الكل
                        </a>
                    </div>

                    <div class="mt-5 space-y-3">
                        @forelse ($recentBookings as $booking)
                            <a href="{{ route('bookings.show', $booking->id) }}"
                               class="flex items-center justify-between gap-4 rounded-control border border-outline/30 bg-canvas px-4 py-4 transition hover:border-brand/40 hover:bg-brand/5">
                                <div class="min-w-0">
                                    <p class="truncate font-bold text-ink">
                                        {{ $booking->adventure?->title ?? 'مغامرة غير متاحة' }}
                                    </p>
                                    <p class="mt-1 text-xs text-muted">
                                        {{ $booking->created_at?->format('Y/m/d') }} · {{ $booking->participants_count }} مشاركين
                                    </p>
                                </div>
                                <div class="shrink-0 text-left">
                                    <p class="font-bold text-brand">{{ number_format($booking->total_amount, 2) }} د.أ</p>
                                    <p class="mt-1 text-xs text-muted">
                                        {{ in_array($booking->status, ['Accepted', 'Confirmed']) ? 'مؤكد' : ($booking->status === 'Cancelled' ? 'ملغي' : 'قيد الانتظار') }}
                                    </p>
                                </div>
                            </a>
                        @empty
                            <div class="rounded-control border border-dashed border-outline/60 bg-canvas px-5 py-10 text-center">
                                <p class="font-bold text-ink">لم تحجز أي مغامرة بعد</p>
                                <p class="mt-2 text-sm text-muted">ابدأ رحلتك الأولى من صفحة المغامرات.</p>
                                <a href="{{ route('adventuresAll.index') }}"
                                   class="mt-5 inline-flex min-h-11 items-center rounded-control bg-brand px-5 py-3 text-sm font-bold text-white">
                                    استكشف المغامرات
                                </a>
                            </div>
                        @endforelse
                    </div>
                </x-ui.card>

                <x-ui.card padding="lg">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="font-display text-xl font-bold text-ink">تقييماتي الأخيرة</h2>
                        @if ($reviewCount)
                            <span class="rounded-full bg-accent/10 px-3 py-1 text-xs font-bold text-accent">{{ $reviewCount }} تقييم</span>
                        @endif
                    </div>

                    <div class="mt-5 space-y-3">
                        @forelse ($recentReviews as $review)
                            @if ($review->adventure)
                                <a href="{{ route('reviews.show', ['adventure' => $review->adventure, 'review' => $review]) }}"
                                   class="flex items-start justify-between gap-4 rounded-control border border-outline/30 px-4 py-4 transition hover:border-brand/40">
                                    <div>
                                        <p class="font-bold text-ink">{{ $review->adventure->title }}</p>
                                        <p class="mt-1 line-clamp-2 text-sm leading-7 text-muted">{{ $review->comment }}</p>
                                    </div>
                                    <span class="shrink-0 rounded-full bg-accent/10 px-3 py-1 text-sm font-bold text-accent">
                                        {{ $review->rating }} ★
                                    </span>
                                </a>
                            @endif
                        @empty
                            <p class="rounded-control border border-dashed border-outline/60 px-5 py-8 text-center text-sm text-muted">
                                لم تكتب أي تقييمات بعد.
                            </p>
                        @endforelse
                    </div>
                </x-ui.card>
            </div>
        </div>
    </div>
</section>

<x-ui.modal :name="$editModalName" title="تعديل الملف الشخصي" size="lg">
    @include('adventurers.partials.form', [
        'adventurer' => $adventurer,
        'formAction' => route('adventurer.update'),
        'formMethod' => 'PATCH',
        'submitLabel' => 'حفظ التعديلات',
        'modalName' => $editModalName,
    ])
</x-ui.modal>
@endsection
