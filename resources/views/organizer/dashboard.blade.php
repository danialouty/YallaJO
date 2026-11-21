@extends('layouts.dashboard')

@section('dashboard-theme', 'organizer')
@section('dashboard-name', 'مساحة المنظّم')
@section('dashboard-title', 'لوحة تحكم المنظّم')

@section('content')
    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900" role="status">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-2xl bg-surface p-6 shadow-sm">
            <p class="text-sm text-muted">مرحبًا، {{ auth()->user()->name }}</p>
            <h2 class="mt-1 font-display text-2xl font-bold text-ink">تابع نشاطك على يلا جو</h2>
            <p class="mt-2 text-sm text-muted">إحصاءات المغامرات والحجوزات والعملاء المرتبطة بمنظمتك.</p>
        </section>

        <section class="grid gap-4 sm:grid-cols-3" aria-label="إحصاءات المنظّم">
            <article class="rounded-2xl bg-surface p-6 shadow-sm">
                <p class="text-sm text-muted">مغامراتي</p>
                <p class="mt-2 text-3xl font-extrabold text-forest">{{ $adventuresCount }}</p>
            </article>
            <article class="rounded-2xl bg-surface p-6 shadow-sm">
                <p class="text-sm text-muted">المستخدمون الذين حجزوا رحلاتي</p>
                <p class="mt-2 text-3xl font-extrabold text-forest">{{ $usersCount }}</p>
            </article>
            <article class="rounded-2xl bg-surface p-6 shadow-sm">
                <p class="text-sm text-muted">إجمالي الحجوزات</p>
                <p class="mt-2 text-3xl font-extrabold text-forest">{{ $bookingsCount }}</p>
            </article>
        </section>

        <section class="grid gap-4 sm:grid-cols-2">
            <a href="{{ route('adventures.index') }}" class="rounded-2xl border border-outline/30 bg-surface p-5 transition hover:border-brand">
                <h2 class="font-bold text-ink">إدارة المغامرات</h2>
                <p class="mt-1 text-sm text-muted">أضف مغامراتك وحدّث بياناتها وصورها.</p>
            </a>
            <a href="{{ route('adventure-schedules.index') }}" class="rounded-2xl border border-outline/30 bg-surface p-5 transition hover:border-brand">
                <h2 class="font-bold text-ink">مواعيد المغامرات</h2>
                <p class="mt-1 text-sm text-muted">نظّم المواعيد والمرشدين والسعة وبرنامج الأيام.</p>
            </a>
        </section>
    </div>
@endsection
