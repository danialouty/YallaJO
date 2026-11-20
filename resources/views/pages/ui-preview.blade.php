
@extends('layouts.site')

@section('title', 'معاينة المكونات | YallaJO')

@section('content')

<section
    class="mx-auto w-full max-w-[1360px]
           px-4 py-12 sm:px-6 lg:px-12"
    aria-labelledby="preview-heading"
>

    {{-- Page Heading --}}
    <header class="mb-10 space-y-3">

        <p class="text-sm font-semibold text-brand">
            YallaJO Design System
        </p>

        <h1
            id="preview-heading"
            class="font-display text-3xl font-bold text-ink
                   md:text-4xl"
        >
            معاينة المكونات المشتركة
        </h1>

        <p class="max-w-2xl leading-8 text-muted">
            هذه صفحة تجريبية لاختبار المكونات
            المشتركة قبل استخدامها في صفحات المشروع.
        </p>

    </header>


    {{-- Components Grid --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- Buttons Card --}}
        <x-ui.card>

            <h2 class="font-display text-xl font-bold">
                Button Component
            </h2>

            <p class="mt-2 text-sm text-muted">
                جرّب فتح نافذتي الإنشاء والتعديل.
            </p>

            <div class="mt-6 flex flex-wrap gap-3">

                <x-ui.button
                    type="button"
                    variant="primary"
                    data-modal-open="preview-create"
                >
                    إضافة عنصر
                </x-ui.button>

                <x-ui.button
                    type="button"
                    variant="secondary"
                    data-modal-open="preview-edit"
                >
                    تعديل عنصر
                </x-ui.button>

                <x-ui.button
                    variant="outline"
                    :disabled="true"
                >
                    زر معطل
                </x-ui.button>

            </div>

        </x-ui.card>


        {{-- Inputs Card --}}
        <x-ui.card variant="outline">

            <h2 class="font-display text-xl font-bold">
                Input Component
            </h2>

            <p class="mt-2 text-sm text-muted">
                حقول الإدخال الموحدة في YallaJO.
            </p>

            <div class="mt-6 space-y-5">

                <x-ui.input
                    name="preview_name"
                    id="preview-page-name"
                    label="الاسم الكامل"
                    value="مستخدم تجريبي"
                    required
                />

                <x-ui.input
                    name="preview_email"
                    id="preview-page-email"
                    label="البريد الإلكتروني"
                    type="email"
                    placeholder="example@email.com"
                    hint="هذا الحقل للمعاينة فقط."
                />

            </div>

        </x-ui.card>


        {{-- General Card --}}
        <x-ui.card
            variant="soft"
            class="lg:col-span-2"
        >

            <h2 class="font-display text-xl font-bold">
                Card Component
            </h2>

            <p class="mt-3 leading-8 text-muted">
                نستطيع استخدام هذه البطاقة في صفحات
                المغامرات والحجوزات ولوحات التحكم،
                مع الحفاظ على الألوان والمسافات المشتركة.
            </p>

        </x-ui.card>

    </div>


    {{-- Create Modal --}}
    <x-ui.modal
        name="preview-create"
        title="إضافة عنصر تجريبي"
        size="md"
    >

        <div class="space-y-5">

            <p class="text-sm text-muted">
                هذه نافذة معاينة فقط.
                لن يتم حفظ أي بيانات.
            </p>

            <x-ui.input
                name="preview_create_title"
                id="preview-create-title"
                label="اسم العنصر"
                placeholder="أدخل الاسم"
                required
            />

            <div class="flex justify-end gap-3">

                <x-ui.button
                    type="button"
                    variant="outline"
                    data-modal-close
                >
                    إغلاق
                </x-ui.button>

            </div>

        </div>

    </x-ui.modal>


    {{-- Edit Modal --}}
    <x-ui.modal
        name="preview-edit"
        title="تعديل عنصر تجريبي"
        size="md"
    >

        <div class="space-y-5">

            <p class="text-sm text-muted">
                اختبر تعديل الحقل وإغلاق النافذة.
                لن يتم إرسال البيانات.
            </p>

            <x-ui.input
                name="preview_edit_title"
                id="preview-edit-title"
                label="اسم العنصر"
                value="عنصر تجريبي"
                required
            />

            <div class="flex justify-end gap-3">

                <x-ui.button
                    type="button"
                    variant="outline"
                    data-modal-close
                >
                    إغلاق
                </x-ui.button>

            </div>

        </div>

    </x-ui.modal>

</section>

@endsection
