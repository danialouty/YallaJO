@extends('layouts.dashboard')

@section('title', 'الحجوزات | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', 'الحجوزات')

@section('content')
    <x-admin.record-index index-route="admin.bookings.index" :records="$records" :filters="$filters"
        blocked-create="إنشاء الحجوزات من لوحة الإدارة غير متاح إلى أن تُعتمد آلية الدفعة المرتبطة."
        search-label="رقم الحجز" search-type="number" :selects="['status' => ['الحالة', ['Pending' => 'قيد الانتظار', 'Accepted' => 'مقبول', 'Cancelled' => 'ملغى']]]"
        :headings="['رقم الحجز', 'المغامر', 'المغامرة', 'الحالة', 'المشاركون', 'الإجمالي', 'التفاصيل']">
        @foreach ($records as $record)
            <tr>
                <td class="[overflow-wrap:anywhere]"><bdi>#{{ $record->id }}</bdi></td>
                <td class="[overflow-wrap:anywhere]">{{ $record->adventurer?->user?->name ?? 'غير متاح' }}</td>
                <td class="[overflow-wrap:anywhere]">{{ $record->adventure?->title ?? 'غير متاحة' }}</td>
                <td class="[overflow-wrap:anywhere]">{{ ['Pending' => 'قيد الانتظار', 'Accepted' => 'مقبول', 'Cancelled' => 'ملغى'][$record->status] ?? $record->status }}</td>
                <td class="[overflow-wrap:anywhere]">{{ $record->participants_count }}</td>
                <td class="[overflow-wrap:anywhere]"><bdi>{{ $record->total_amount }}</bdi> د.أ</td>
                <td>
                    @if ($record->status === 'Pending' && $record->adventure?->status === 'Active' && !$record->payment_exists && !$record->linked_review_id)
                        <x-admin.action-link :href="route('admin.bookings.edit', $record)">تعديل</x-admin.action-link>
                    @endif
                    <a href="{{ route('admin.bookings.show', $record) }}" class="inline-flex min-h-11 items-center rounded-control text-brand underline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand">
                        عرض<span class="sr-only"> السجل رقم {{ $record->id }}</span>
                    </a>
                </td>
            </tr>
        @endforeach
    </x-admin.record-index>
@endsection
