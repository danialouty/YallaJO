@extends('layouts.site')

@section('title', 'تفاصيل التقييم | ' . $adventure->title . ' | YallaJO')

@section('content')
    @php
        $viewer = auth()->user();
        $canEdit = false;

        if ($viewer?->role === 'Adventurer' && $review->adventurer_id !== null) {
            $canEdit = $viewer->relationLoaded('adventurer')
                ? $viewer->getRelation('adventurer')?->id === $review->adventurer_id
                : $viewer->adventurer()->whereKey($review->adventurer_id)->exists();
        }

        $modalName = 'edit-review-' . $review->getKey();
        $deleteModalName = 'delete-review-' . $review->getKey();
    @endphp

    <section class="bg-canvas py-8 sm:py-14" aria-labelledby="review-heading">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <a
                href="{{ route('reviews.index', ['adventure' => $adventure]) }}"
                class="mb-6 inline-flex min-h-11 items-center gap-2 rounded-control text-sm
                       font-semibold text-brand hover:underline focus-visible:outline-2
                       focus-visible:outline-offset-4 focus-visible:outline-brand"
            >
                <span aria-hidden="true">→</span>
                العودة إلى التقييمات
            </a>

            <header class="mb-6">
                <h1 id="review-heading" class="font-display text-3xl font-bold text-ink sm:text-4xl">
                    تفاصيل التقييم
                </h1>
                <p class="mt-2 text-lg text-muted [overflow-wrap:anywhere]">{{ $adventure->title }}</p>
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

            @include('reviews.partials.review-card', ['review' => $review])

            @if ($canEdit)
                <a
                    id="{{ $modalName }}-link"
                    href="{{ route('reviews.edit', ['adventure' => $adventure, 'review' => $review]) }}"
                    data-modal-open="{{ $modalName }}"
                    aria-haspopup="dialog" aria-controls="{{ $modalName }}"
                    class="mt-5 inline-flex min-h-11 items-center justify-center rounded-control bg-brand px-5
                           py-3 text-sm font-semibold text-white hover:bg-brand-dark focus-visible:outline-2
                           focus-visible:outline-offset-2 focus-visible:outline-brand"
                >
                    تعديل التقييم
                </a>

                <details id="{{ $deleteModalName }}-fallback" class="mt-4">
                    <summary
                        id="{{ $deleteModalName }}-trigger"
                        data-review-delete-trigger="{{ $deleteModalName }}"
                        class="inline-flex min-h-11 cursor-pointer items-center rounded-control border border-danger/40
                               px-5 py-3 text-sm font-semibold text-danger hover:bg-danger/5 focus-visible:outline-2
                               focus-visible:outline-offset-2 focus-visible:outline-danger"
                    >
                        حذف التقييم
                    </summary>

                    <form
                        id="{{ $deleteModalName }}-form"
                        action="{{ route('reviews.destroy', ['adventure' => $adventure, 'review' => $review]) }}"
                        method="POST"
                        class="my-3 space-y-4 rounded-control border border-danger/30 bg-surface p-4"
                    >
                        @csrf
                        @method('DELETE')

                        <p id="{{ $deleteModalName }}-warning" class="text-sm leading-7 text-ink">
                            سيُحذف هذا التقييم نهائيًا. هل تريد المتابعة؟
                        </p>

                        <div class="flex flex-wrap gap-3">
                            <a
                                id="{{ $deleteModalName }}-cancel"
                                data-review-delete-cancel="{{ $deleteModalName }}"
                                href="{{ route('reviews.show', ['adventure' => $adventure, 'review' => $review]) }}"
                                class="inline-flex min-h-11 items-center justify-center rounded-control border border-outline
                                       px-4 py-2 text-sm font-semibold text-ink hover:bg-brand/5 focus-visible:outline-2
                                       focus-visible:outline-offset-2 focus-visible:outline-brand"
                            >
                                إلغاء
                            </a>
                            <button
                                type="submit"
                                class="min-h-11 rounded-control bg-danger px-4 py-2 text-sm font-semibold text-white
                                       hover:bg-danger/90 focus-visible:outline-2 focus-visible:outline-offset-2
                                       focus-visible:outline-danger"
                            >
                                تأكيد الحذف
                            </button>
                        </div>
                    </form>
                </details>
            @endif
        </div>
    </section>

    @if ($canEdit)
        <x-ui.modal :name="$modalName" :error-bag="$modalName" title="تعديل التقييم" size="lg">
            @include('reviews.partials.edit-form', ['modalName' => $modalName])
        </x-ui.modal>

        <x-ui.modal :name="$deleteModalName" title="تأكيد حذف التقييم" size="sm"
            :aria-describedby="$deleteModalName . '-warning'">
            <div id="{{ $deleteModalName }}-content"></div>
        </x-ui.modal>
    @endif
@endsection
