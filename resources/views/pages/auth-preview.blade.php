
@php
    /*
     * UI preview only.
     * This is not real authentication.
     */

    $isRegister = request()->query('mode') === 'register';
@endphp

@extends('layouts.auth')

@section(
    'title',
    $isRegister
        ? 'إنشاء حساب | YallaJO'
        : 'تسجيل الدخول | YallaJO'
)

@section(
    'auth-heading',
    $isRegister
        ? 'إنشاء حساب جديد'
        : 'تسجيل الدخول'
)

@section(
    'auth-description',
    $isRegister
        ? 'انضم إلى يلا جو وابدأ اكتشاف مغامرات الأردن.'
        : 'مرحبًا بعودتك! أدخل بيانات حسابك للمتابعة.'
)

@section('content')

    <div class="space-y-6">

        {{-- Preview Notice --}}
        <div
            role="note"
            class="rounded-control border
                   border-accent/20 bg-accent/5
                   px-4 py-3"
        >
            <p class="text-sm font-semibold text-ink">
                معاينة واجهة المصادقة
            </p>

            <p class="mt-1 text-xs leading-6 text-muted">
                هذه الصفحة لا تنشئ حسابات
                ولا تسجّل الدخول فعليًا.
            </p>
        </div>


        {{-- Preview Fields --}}
        <div class="space-y-5">

            @if ($isRegister)

                {{-- Full Name --}}
                <x-ui.input
                    name="preview_name"
                    id="auth-preview-name"
                    label="الاسم الكامل"
                    type="text"
                    placeholder="أدخل اسمك الكامل"
                    autocomplete="off"
                    required
                />

            @endif


            {{-- Email --}}
            <x-ui.input
                name="preview_email"
                id="auth-preview-email"
                label="البريد الإلكتروني"
                type="email"
                placeholder="example@email.com"
                autocomplete="off"
                required
            />


            {{-- Password --}}
            <x-ui.input
                name="preview_password"
                id="auth-preview-password"
                label="كلمة المرور"
                type="password"
                placeholder="أدخل كلمة المرور"
                autocomplete="off"
                required
            />


            @if ($isRegister)

                {{-- Confirm Password --}}
                <x-ui.input
                    name="preview_password_confirmation"
                    id="auth-preview-confirm-password"
                    label="تأكيد كلمة المرور"
                    type="password"
                    placeholder="أعد إدخال كلمة المرور"
                    autocomplete="off"
                    required
                />

            @endif

        </div>


        {{-- Preview Action --}}
        <div class="space-y-3">

            <x-ui.button
                type="button"
                variant="primary"
                size="lg"
                :disabled="true"
                class="w-full"
            >
                {{ $isRegister
                    ? 'إنشاء الحساب'
                    : 'تسجيل الدخول' }}
            </x-ui.button>

            <p class="text-center text-xs text-muted">
                الزر معطّل لأن هذه صفحة معاينة فقط.
            </p>

        </div>

    </div>

@endsection


{{-- Optional Footer Links --}}
@section('auth-footer')

    @if ($isRegister)

        <p>
            لديك حساب بالفعل؟

            <a
                href="{{ route('auth.preview', [
                    'mode' => 'login'
                ]) }}"
                class="font-semibold text-brand
                       hover:underline"
            >
                تسجيل الدخول
            </a>
        </p>

    @else

        <p>
            ليس لديك حساب؟

            <a
                href="{{ route('auth.preview', [
                    'mode' => 'register'
                ]) }}"
                class="font-semibold text-brand
                       hover:underline"
            >
                إنشاء حساب جديد
            </a>
        </p>

    @endif

@endsection
