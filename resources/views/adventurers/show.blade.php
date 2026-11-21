
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
        'Hiking' => 'المشي والمسارات الجبلية',
        'Camping' => 'التخييم',
        'Diving' => 'الغوص',
        'kayaking' => 'التجديف',
        'climbing' => 'التسلق',
    ];

    $interests = $adventurer->interests ?? [];

    $interests = is_array($interests)
        ? $interests
        : [];
@endphp

<section class="min-h-screen bg-canvas py-10 sm:py-16">

    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="mb-8">

            <p class="mb-3 text-sm font-semibold text-brand">
                حساب المغامر
            </p>

            <h1 class="font-display text-3xl font-bold text-ink">
                ملفي الشخصي
            </h1>

            <p class="mt-3 text-sm text-muted">
                تعرّف على بيانات ملفك واهتماماتك في المغامرات.
            </p>

        </div>

        {{-- Success Message --}}
        @if (session('success'))

            <div
                role="status"
                class="mb-6 rounded-control border
                       border-forest/30 bg-forest/10
                       p-4 text-sm text-forest"
            >
                {{ session('success') }}
            </div>

        @endif

        {{-- Error Message --}}
        @if (session('error'))

            <div
                role="alert"
                class="mb-6 rounded-control border
                       border-danger/30 bg-danger/5
                       p-4 text-sm text-danger"
            >
                {{ session('error') }}
            </div>

        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Personal Information --}}
            <div class="lg:col-span-1">

                <x-ui.card padding="lg" class="text-center">

                    {{-- Avatar --}}
                    <div
                        class="mx-auto flex size-20
                               items-center justify-center
                               rounded-full bg-brand
                               font-display text-3xl
                               font-bold text-white"
                        aria-hidden="true"
                    >
                        {{ mb_substr(trim($user->name), 0, 1) }}
                    </div>

                    <h2
                        class="mt-5 font-display
                               text-xl font-bold text-ink"
                    >
                        {{ $user->name }}
                    </h2>

                    <p
                        class="mt-2 break-all text-sm text-muted"
                        dir="ltr"
                    >
                        {{ $user->email }}
                    </p>

                    <span
                        class="mt-5 inline-flex rounded-full
                               bg-forest/10 px-4 py-2
                               text-xs font-semibold text-forest"
                    >
                        مغامر في YallaJO
                    </span>

                    <div
                        class="mt-8 border-t
                               border-outline/30 pt-6"
                    >
                        <p class="text-xs text-muted">
                            تاريخ إنشاء الملف
                        </p>

                        <p
                            class="mt-2 font-semibold text-ink"
                            dir="ltr"
                        >
                            {{ $adventurer->created_at?->format('Y-m-d') ?? '—' }}
                        </p>
                    </div>

                </x-ui.card>

            </div>


            {{-- Adventurer Details --}}
            <div class="space-y-6 lg:col-span-2">

                <x-ui.card padding="lg">

                    <h2
                        class="font-display text-xl
                               font-bold text-ink"
                    >
                        معلومات المغامر
                    </h2>

                    <div
                        class="mt-6 grid grid-cols-1
                               gap-6 sm:grid-cols-2"
                    >

                        {{-- Experience --}}
                        <div>
                            <p class="text-sm text-muted">
                                مستوى الخبرة
                            </p>

                            <p
                                class="mt-2 font-semibold
                                       text-ink"
                            >
                                {{ $experienceLabels[$adventurer->experience_level]
                                    ?? $adventurer->experience_level }}
                            </p>
                        </div>

                        {{-- Location --}}
                        <div>
                            <p class="text-sm text-muted">
                                المدينة أو المنطقة
                            </p>

                            <p
                                class="mt-2 font-semibold
                                       text-ink"
                            >
                                {{ $adventurer->location ?: 'غير محددة' }}
                            </p>
                        </div>

                    </div>

                </x-ui.card>


                {{-- Interests --}}
                <x-ui.card padding="lg">

                    <h2
                        class="font-display text-xl
                               font-bold text-ink"
                    >
                        اهتماماتي
                    </h2>

                    <p class="mt-2 text-sm text-muted">
                        الأنشطة والمغامرات التي أفضلها.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">

                        @forelse ($interests as $interest)

                            <span
                                class="inline-flex rounded-full
                                       border border-forest/20
                                       bg-forest/10 px-4 py-2
                                       text-sm font-medium
                                       text-forest"
                            >
                                {{ $interestLabels[$interest] ?? $interest }}
                            </span>

                        @empty

                            <p class="text-sm text-muted">
                                لم تتم إضافة اهتمامات بعد.
                            </p>

                        @endforelse

                    </div>

                </x-ui.card>


                {{-- Actions --}}
                <div class="flex flex-wrap gap-3">

                    <a
                        href="{{ route('home') }}"
                        class="inline-flex min-h-11 items-center
                               justify-center rounded-control
                               border border-outline
                               bg-surface px-5 py-3
                               text-sm font-semibold text-ink
                               hover:bg-brand/5"
                    >
                        العودة إلى الرئيسية
                    </a>

                    <a
                        id="adventurer-edit-link"
                        href="{{ route('adventurer.edit') }}"
                        aria-haspopup="dialog"
                        aria-controls="{{ $editModalName }}"
                        class="inline-flex min-h-11 items-center
                               justify-center rounded-control
                               bg-brand px-5 py-3 text-sm font-semibold
                               text-white hover:bg-brand-dark
                               focus-visible:outline-2 focus-visible:outline-offset-2
                               focus-visible:outline-brand"
                    >
                        تعديل الملف
                    </a>

                </div>

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

@push('scripts')
    <script>
        (() => {
            const editLink = document.getElementById('adventurer-edit-link');
            const dialog = document.getElementById(editLink?.getAttribute('aria-controls'));

            if (typeof dialog?.showModal !== 'function') {
                return;
            }

            editLink.addEventListener('click', (event) => {
                const target = editLink.getAttribute('target');

                if (event.defaultPrevented || event.button !== 0 || event.ctrlKey || event.metaKey
                    || event.shiftKey || event.altKey || (target && target.toLowerCase() !== '_self')) {
                    return;
                }

                try {
                    if (!dialog.open) {
                        dialog.showModal();
                    }
                } catch {
                    return;
                }

                if (dialog.open) {
                    event.preventDefault();
                }
            });

            dialog.addEventListener('close', () => editLink.focus());
        })();
    </script>
@endpush
