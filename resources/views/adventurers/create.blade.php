
@extends('layouts.site')

@section('title', 'إنشاء ملف مغامر | YallaJO')

@section(
    'meta_description',
    'أنشئ ملفك الشخصي وخصص تجربتك في مغامرات الأردن.'
)

@section('content')

<section class="bg-canvas py-10 sm:py-16">

    <div
        class="mx-auto max-w-6xl
               px-4 sm:px-6 lg:px-8"
    >
        {{-- Breadcrumb --}}
        <nav
            aria-label="مسار الصفحة"
            class="mb-8 text-sm text-muted"
        >
            <a
                href="{{ route('adventures.index') }}"
                class="hover:text-brand"
            >
                المغامرات
            </a>

            <span class="mx-2" aria-hidden="true">
                /
            </span>

            <span class="font-semibold text-brand">
                إنشاء ملف مغامر
            </span>
        </nav>

        <div
            class="grid grid-cols-1
                   gap-8 lg:grid-cols-5"
        >

            {{-- Main Form --}}
            <div class="min-w-0 lg:col-span-3">

                <div class="mb-8 space-y-3">
                    <span
                        class="inline-flex rounded-full
                               bg-forest/10 px-4 py-2
                               text-xs font-bold text-forest"
                    >
                        ملف المغامر
                    </span>

                    <h1
                        class="font-display text-3xl
                               font-bold text-ink
                               sm:text-4xl"
                    >
                        رحلتك تبدأ من هنا
                    </h1>

                    <p
                        class="max-w-xl text-sm
                               leading-8 text-muted"
                    >
                        أخبرنا بمستوى خبرتك
                        والمغامرات التي تحبها،
                        لنساعدك على اكتشاف
                        التجارب المناسبة لك.
                    </p>
                </div>

                @if (session('error'))
                    <div
                        role="alert"
                        class="mb-6 rounded-control
                               border border-danger/30
                               bg-danger/5 p-4
                               text-sm text-danger"
                    >
                        {{ session('error') }}
                    </div>
                @endif

                <x-ui.card padding="lg">

                    <h2
                        class="mb-6 font-display
                               text-xl font-bold text-ink"
                    >
                        معلوماتك كمغامر
                    </h2>

                    @include(
                        'adventurers.partials.form',
                        [
                            'formAction' => route('adventurer.store'),
                            'formMethod' => 'POST',
                            'submitLabel' => 'إنشاء الملف الشخصي',
                            'adventurer' => null,
                        ]
                    )

                </x-ui.card>
            </div>

            {{-- Supporting Information --}}
            <aside
                class="lg:col-span-2"
                aria-label="معلومات عن ملف المغامر"
            >
                <div
                    class="rounded-card bg-forest
                           p-7 text-white
                           lg:sticky lg:top-28"
                >
                    <div
                        class="mb-6 flex size-14
                               items-center justify-center
                               rounded-card bg-white/15
                               text-3xl"
                        aria-hidden="true"
                    >
                        🧭
                    </div>

                    <h2
                        class="font-display
                               text-2xl font-bold"
                    >
                        اكتشف الأردن بطريقتك
                    </h2>

                    <p
                        class="mt-4 text-sm
                               leading-8 text-white/85"
                    >
                        ملفك الشخصي هو بداية
                        التعرف على الأنشطة
                        التي تناسب خبرتك واهتماماتك.
                    </p>

                    <ul
                        class="mt-8 space-y-4
                               text-sm leading-7"
                    >
                        <li>✓ حدد مستوى خبرتك.</li>
                        <li>✓ اختر الأنشطة المفضلة لديك.</li>
                        <li>✓ أضف منطقتك إن رغبت.</li>
                    </ul>

                    <p
                        class="mt-8 border-t
                               border-white/20 pt-5
                               text-xs leading-6
                               text-white/70"
                    >
                        يمكنك تحديث هذه المعلومات
                        لاحقًا من ملفك الشخصي.
                    </p>
                </div>
            </aside>

        </div>
    </div>

</section>

@endsection
