@extends('layouts.dashboard')

@section('title', 'التقييمات | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', 'التقييمات')

@section('content')
    <x-admin.record-index index-route="admin.reviews.index" :records="$records" :filters="$filters"
        blocked-create="إضافة تقييم من لوحة الإدارة تتطلب ضمان تقييم واحد لكل حجز عبر جميع مسارات الإنشاء."
        search-label="عنوان المغامرة أو نص التعليق" :selects="['rating' => ['الدرجة', [1 => '١ من ٥', 2 => '٢ من ٥', 3 => '٣ من ٥', 4 => '٤ من ٥', 5 => '٥ من ٥']], 'is_reported' => ['البلاغات', ['1' => 'مُبلَّغ عنه فقط', '0' => 'غير مُبلَّغ عنه']]]"
        :headings="['رقم التقييم', 'المغامر', 'المغامرة', 'الدرجة', 'مُبلَّغ عنه؟', 'التاريخ', 'التفاصيل']">
        @foreach ($records as $record)
            <tr>
                <td class="[overflow-wrap:anywhere]"><bdi>#{{ $record->id }}</bdi></td>
                <td class="[overflow-wrap:anywhere]">{{ $record->adventurer?->user?->name ?? 'غير متاح' }}</td>
                <td class="[overflow-wrap:anywhere]">{{ $record->adventure?->title ?? 'غير متاحة' }}</td>
                <td class="[overflow-wrap:anywhere]">{{ $record->rating }} من ٥</td>
                <td class="[overflow-wrap:anywhere]">{{ $record->created_at?->toDateString() ?? 'غير مسجّل' }}</td>
                <td>
                    <a href="{{ route('admin.reviews.show', $record) }}" class="inline-flex min-h-11 items-center rounded-control text-brand underline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand">
                        عرض<span class="sr-only"> السجل رقم {{ $record->id }}</span>
                    </a>
                </td>
            </tr>
        @endforeach
    </x-admin.record-index>
@endsection
