
@php
    /*
     * Preview only.
     * This value controls appearance, not permissions.
     */
    $isAdmin = request()->query('theme') === 'admin';

    $previewTheme = $isAdmin
        ? 'admin'
        : 'organizer';

    $dashboardName = $isAdmin
        ? 'لوحة الإدارة'
        : 'بوابة المنظمين';

    $dashboardTitle = $isAdmin
        ? 'نظرة عامة على المنصة'
        : 'لوحة تحكم المنظّم';

    $metrics = $isAdmin
        ? [
            'طلبات الموافقة',
            'إجمالي المستخدمين',
            'المغامرات المسجلة',
            'العمولات',
        ]
        : [
            'مغامراتي',
            'الحجوزات القادمة',
            'المشاركون',
            'الإيرادات',
        ];
@endphp

@extends('layouts.dashboard')

@section('title', 'Dashboard Preview | YallaJO')

@section('dashboard-theme', $previewTheme)

@section('dashboard-name', $dashboardName)

@section('dashboard-title', $dashboardTitle)


{{-- Header Actions --}}
@section('dashboard-header-actions')

    <div
        class="flex items-center gap-1
               rounded-control border
               border-outline/40
               bg-canvas p-1"
        aria-label="اختيار معاينة لوحة التحكم"
    >

        <a
            href="{{ route('dashboard.preview', [
                'theme' => 'organizer'
            ]) }}"
            @if (!$isAdmin)
                aria-current="page"
            @endif
            class="rounded-control px-3 py-2
                   text-xs font-semibold
                   {{ !$isAdmin
                        ? 'bg-brand text-white'
                        : 'text-muted hover:bg-surface' }}"
        >
            Organizer
        </a>

        <a
            href="{{ route('dashboard.preview', [
                'theme' => 'admin'
            ]) }}"
            @if ($isAdmin)
                aria-current="page"
            @endif
            class="rounded-control px-3 py-2
                   text-xs font-semibold
                   {{ $isAdmin
                        ? 'bg-brand text-white'
                        : 'text-muted hover:bg-surface' }}"
        >
            Admin
        </a>

    </div>

@endsection


{{-- Dashboard Content --}}
@section('content')

    <div class="space-y-8">

        {{-- Preview Notice --}}
        <div
            class="rounded-card border
                   border-accent/25
                   bg-accent/5 px-5 py-4"
            role="note"
        >
            <p class="font-semibold text-ink">
                وضع معاينة الواجهات
            </p>

            <p class="mt-2 text-sm leading-7 text-muted">
                هذه الصفحة مخصصة لاختبار تصميم
                لوحة التحكم المشتركة فقط.

                لا توجد بيانات حقيقية أو عمليات
                مرتبطة بقاعدة البيانات.
            </p>
        </div>


        {{-- Dashboard Heading --}}
        <div class="space-y-2">

            <h2
                class="font-display text-2xl
                       font-bold text-ink"
            >
                {{ $dashboardTitle }}
            </h2>

            <p class="text-sm text-muted">
                {{ $isAdmin
                    ? 'معاينة واجهة إدارة المستخدمين والموافقات.'
                    : 'معاينة واجهة إدارة المغامرات والحجوزات.' }}
            </p>

        </div>


        {{-- Statistics Cards --}}
        <section
            aria-labelledby="preview-stats-title"
            class="space-y-4"
        >

            <h3
                id="preview-stats-title"
                class="font-display text-lg
                       font-bold text-ink"
            >
                الإحصائيات
            </h3>

            <div
                class="grid grid-cols-1 gap-5
                       sm:grid-cols-2 xl:grid-cols-4"
            >

                @foreach ($metrics as $metric)

                    <x-ui.card>

                        <p class="text-sm text-muted">
                            {{ $metric }}
                        </p>

                        <p
                            class="mt-4 font-display
                                   text-3xl font-bold
                                   text-ink"
                        >
                            —
                        </p>

                        <p
                            class="mt-3 text-xs
                                   text-muted"
                        >
                            بانتظار ربط البيانات
                        </p>

                    </x-ui.card>

                @endforeach

            </div>

        </section>


        {{-- Dashboard Sections --}}
        <div
            class="grid grid-cols-1 gap-6
                   xl:grid-cols-3"
        >

            {{-- Main Content Card --}}
            <x-ui.card class="xl:col-span-2">

                <div class="space-y-3">

                    <h3
                        class="font-display text-xl
                               font-bold text-ink"
                    >
                        {{ $isAdmin
                            ? 'طلبات الموافقة الأخيرة'
                            : 'المغامرات القادمة' }}
                    </h3>

                    <p class="text-sm text-muted">
                        {{ $isAdmin
                            ? 'ستظهر طلبات المنظمين والأنشطة هنا.'
                            : 'ستظهر المغامرات والمواعيد القادمة هنا.' }}
                    </p>

                </div>

                <div
                    class="mt-6 flex min-h-44
                           flex-col items-center
                           justify-center gap-3
                           rounded-card border
                           border-dashed
                           border-outline/60
                           bg-canvas p-6
                           text-center"
                >

                    <p class="font-semibold text-muted">
                        لا توجد بيانات للمعاينة
                    </p>

                    <p class="text-sm text-muted">
                        سيتم عرض البيانات بعد ربط
                        الواجهة بـ Controller.
                    </p>

                </div>

            </x-ui.card>


            {{-- Secondary Content Card --}}
            <x-ui.card>

                <h3
                    class="font-display text-xl
                           font-bold text-ink"
                >
                    {{ $isAdmin
                        ? 'إدارة المنصة'
                        : 'إدارة الأنشطة' }}
                </h3>

                <p
                    class="mt-3 text-sm
                           leading-7 text-muted"
                >
                    {{ $isAdmin
                        ? 'مساحة مخصصة لإجراءات الإدارة والمتابعة.'
                        : 'مساحة مخصصة لإضافة المغامرات ومتابعة الحجوزات.' }}
                </p>

                <div class="mt-6">

                    <x-ui.button
                        type="button"
                        variant="primary"
                        :disabled="true"
                        class="w-full"
                    >
                        {{ $isAdmin
                            ? 'إدارة الموافقات'
                            : 'إضافة مغامرة' }}
                    </x-ui.button>

                </div>

                <p class="mt-3 text-xs text-muted">
                    هذه العملية غير مفعلة
                    في صفحة المعاينة.
                </p>

            </x-ui.card>

        </div>

    </div>

@endsection
