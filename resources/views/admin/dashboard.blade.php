@extends('layouts.dashboard')

@section('title', 'نظرة عامة | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', 'نظرة عامة')

@section('content')
    <div class="space-y-8 pb-8">
        <x-ui.card variant="soft" padding="lg">
            <div class="flex flex-wrap items-center justify-between gap-5">
                <h2 class="font-display text-2xl font-bold text-ink [overflow-wrap:anywhere]">
                    أهلًا، <bdi>{{ auth()->user()->name }}</bdi>
                </h2>
                <nav aria-label="روابط الإدارة السريعة" class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.organizers.index') }}" class="inline-flex min-h-11 items-center rounded-control bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">
                        إدارة المنظّمين
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="inline-flex min-h-11 items-center rounded-control border border-outline bg-surface px-4 py-2 text-sm font-semibold text-ink hover:bg-brand/5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">
                        عرض المدفوعات
                    </a>
                </nav>
            </div>
        </x-ui.card>

        <section aria-labelledby="admin-platform-heading" class="space-y-4">
            <h2 id="admin-platform-heading" class="text-xl font-bold">المنصّة</h2>
            <dl class="grid grid-cols-2 gap-3 lg:grid-cols-5">
                @foreach (['users' => 'إجمالي المستخدمين', 'adventurers' => 'المغامرون', 'adventures' => 'إجمالي المغامرات', 'activeAdventures' => 'المغامرات النشطة', 'reviews' => 'التقييمات'] as $metric => $label)
                    <x-ui.card padding="sm">
                        <dt class="text-sm text-muted">{{ $label }}</dt>
                        <dd class="mt-2 font-metrics text-2xl font-semibold"><bdi>{{ number_format($metrics[$metric]) }}</bdi></dd>
                    </x-ui.card>
                @endforeach
            </dl>
        </section>

        <div class="grid gap-4 lg:grid-cols-3">
            <x-ui.card>
                <section aria-labelledby="admin-organizers-heading">
                    <h2 id="admin-organizers-heading" class="mb-4 text-xl font-bold">اعتماد المنظّمين</h2>
                    <dl class="space-y-3">
                        <div class="flex justify-between gap-3">
                            <dt class="text-muted">إجمالي المنظّمين</dt>
                            <dd class="font-semibold"><bdi>{{ number_format($metrics['organizers']) }}</bdi></dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-muted">طلبات بانتظار الموافقة</dt>
                            <dd class="font-semibold"><bdi>{{ number_format($metrics['pendingOrganizers']) }}</bdi></dd>
                        </div>
                    </dl>
                </section>
            </x-ui.card>

            <x-ui.card>
                <section aria-labelledby="admin-bookings-heading">
                    <h2 id="admin-bookings-heading" class="mb-4 text-xl font-bold">الحجوزات</h2>
                    <dl class="space-y-3">
                        @foreach (['bookings' => 'إجمالي الحجوزات', 'pendingBookings' => 'قيد الانتظار', 'acceptedBookings' => 'مقبولة', 'cancelledBookings' => 'ملغاة'] as $metric => $label)
                            <div class="flex justify-between gap-3">
                                <dt class="text-muted">{{ $label }}</dt>
                                <dd class="font-semibold"><bdi>{{ number_format($metrics[$metric]) }}</bdi></dd>
                            </div>
                        @endforeach
                    </dl>
                </section>
            </x-ui.card>

            <x-ui.card>
                <section aria-labelledby="admin-payments-heading">
                    <h2 id="admin-payments-heading" class="mb-4 text-xl font-bold">المدفوعات</h2>
                    <dl class="space-y-3">
                        <div class="flex justify-between gap-3">
                            <dt class="text-muted">إجمالي الدفعات</dt>
                            <dd class="font-semibold"><bdi>{{ number_format($metrics['payments']) }}</bdi></dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-muted">الدفعات المؤكدة</dt>
                            <dd class="font-semibold"><bdi>{{ number_format($metrics['confirmedPayments']) }}</bdi></dd>
                        </div>
                        <div class="border-t border-outline/60 pt-3">
                            <dt class="text-muted">إجمالي مبالغ الدفعات المؤكدة</dt>
                            <dd class="mt-1 font-semibold text-forest"><bdi>{{ number_format((float) $metrics['confirmedPaymentAmount'], 2) }}</bdi> د.أ</dd>
                        </div>
                    </dl>
                </section>
            </x-ui.card>
        </div>
    </div>
@endsection
