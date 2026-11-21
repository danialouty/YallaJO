@extends('layouts.dashboard')

@section('title', 'تفاصيل الحجز | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', 'تفاصيل الحجز')

@section('content')
    <x-admin.record-details back-route="admin.bookings.index" back-label="العودة إلى الحجوزات" :title="'الحجز رقم '.$booking->id" :fields="[
        'المغامر' => $booking->adventurer?->user?->name,
        'المغامرة' => $booking->adventure?->title,
        'الحالة' => ['Pending' => 'قيد الانتظار', 'Accepted' => 'مقبول', 'Cancelled' => 'ملغى'][$booking->status] ?? $booking->status,
        'عدد المشاركين' => $booking->participants_count,
        'سعر المقعد بالدينار الأردني' => $booking->unit_price,
        'الإجمالي بالدينار الأردني' => $booking->total_amount,
        'تاريخ الحجز' => $booking->created_at?->toDateString(),
        'تاريخ الإلغاء' => $booking->cancelled_at?->toDateString(),
    ]">
        @if ($canEdit)
            <x-admin.action-link :href="route('admin.bookings.edit', $booking)">تعديل عدد المشاركين</x-admin.action-link>
        @endif
        @if ($canChange)
            <x-admin.confirm-action :action="route('admin.bookings.cancel', $booking)" method="PATCH" label="إلغاء الحجز"
                warning="سيتغيّر الحجز المعلّق إلى ملغى، ويبقى سجل الحجز محفوظًا." />
        @else
            <p class="text-sm text-muted">التعديل والإلغاء متاحان للحجوزات المعلّقة دون دفعات أو تقييمات مرتبطة.</p>
        @endif

        <x-ui.card padding="lg">
            <h2 class="mb-4 text-lg font-bold">الدفعات المرتبطة</h2>
            @if ($payments->isEmpty())
                <p class="text-muted">لا توجد دفعات في هذه الصفحة.</p>
            @else
                <ul class="mb-4 flex flex-col gap-2">
                    @foreach ($payments as $payment)
                        <li>
                            <a href="{{ route('admin.payments.show', $payment) }}" class="inline-flex min-h-11 flex-wrap items-center gap-2 rounded-control text-brand underline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand">
                                الدفعة رقم {{ $payment->id }} <span><bdi>{{ $payment->total_amount }}</bdi> د.أ</span>
                                <span>{{ ['Pending' => 'قيد الانتظار', 'Confirmed' => 'مؤكدة', 'Canceled' => 'ملغاة'][$payment->status] ?? $payment->status }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
            {{ $payments->links() }}
        </x-ui.card>
    </x-admin.record-details>
@endsection
