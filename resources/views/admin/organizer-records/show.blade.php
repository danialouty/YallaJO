@extends('layouts.dashboard')

@section('title', 'تفاصيل المنظّم | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', 'تفاصيل المنظّم')

@section('content')
    <x-admin.record-details back-route="admin.organizers.index" back-label="العودة إلى المنظّمين"
        :title="'ملف المنظّم رقم '.$organizer->id" :fields="[
            'الاسم' => $organizer->user?->name,
            'البريد الإلكتروني' => $organizer->user?->email,
            'الهاتف' => $organizer->user?->phone_number,
            'النوع' => $types[$organizer->organizer_type] ?? $organizer->organizer_type,
            'الترخيص' => $organizer->license_number,
            'الرابط' => $organizer->social_handle_url,
            'الحالة' => $statuses[$organizer->approval_status] ?? $organizer->approval_status,
            'آخر اعتماد' => $organizer->approved_at?->format('Y-m-d H:i'),
            'المعتمِد' => $organizer->approver?->name,
        ]">
    </x-admin.record-details>
@endsection
