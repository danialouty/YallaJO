@extends('layouts.dashboard')

@section('title', 'تفاصيل الدفعة | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', 'تفاصيل الدفعة')

@section('content')
    <div class="space-y-6 pb-8">
        <a href="{{ route('admin.payments.index') }}" class="inline-flex min-h-11 items-center rounded-control font-semibold text-brand underline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand">
            العودة إلى المدفوعات
        </a>

        <x-ui.card padding="lg">
            <h2 class="mb-4 text-lg font-bold">الدفعة رقم {{ $payment->id }}</h2>
            <dl class="grid gap-5 sm:grid-cols-2">
                <div>
                    <dt class="text-sm text-muted">المبلغ المسجّل</dt>
                    <dd class="font-semibold"><bdi>{{ number_format((float) $payment->total_amount, 2) }}</bdi> د.أ</dd>
                </div>
                <div>
                    <dt class="text-sm text-muted">الحالة</dt>
                    <dd>{{ $statusLabel }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-muted">طريقة الدفع</dt>
                    <dd>{{ $methodLabel }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-muted">تاريخ الدفع</dt>
                    <dd>
                        @if ($payment->paid_at)
                            <time datetime="{{ $payment->paid_at->toIso8601String() }}" dir="ltr">{{ $payment->paid_at->format('Y-m-d H:i') }}</time>
                        @else
                            غير مسجّل
                        @endif
                    </dd>
                </div>
            </dl>
        </x-ui.card>

        @if ($payment->booking)
            <x-ui.card padding="lg">
                <h2 class="mb-4 text-lg font-bold">الحجز رقم {{ $payment->booking->id }}</h2>
                <dl class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm text-muted">المغامرة</dt>
                        <dd class="[overflow-wrap:anywhere]">{{ $payment->booking->adventure?->title ?? 'غير متاحة' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-muted">عدد المشاركين</dt>
                        <dd>{{ $payment->booking->participants_count }}</dd>
                    </div>
                </dl>
            </x-ui.card>
        @endif
    </div>
@endsection
