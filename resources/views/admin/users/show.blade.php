@extends('layouts.dashboard')

@section('title', 'تفاصيل المستخدم | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', 'تفاصيل المستخدم')

@section('content')
    <x-admin.record-details back-route="admin.users.index" back-label="العودة إلى المستخدمون" :title="$user->name" :fields="[
        'رقم المستخدم' => $user->id,
        'البريد الإلكتروني' => $user->email,
        'الدور' => ['Admin' => 'مسؤول النظام', 'Adventurer' => 'مغامر', 'Activity_Organizer' => 'منظّم'][$user->role] ?? $user->role,
        'تاريخ التسجيل' => $user->created_at?->toDateString(),
        'ملف المغامر' => $user->adventurer ? '#'.$user->adventurer->id : 'غير موجود',
        'مستوى خبرة المغامر' => $user->adventurer?->experience_level,
        'موقع المغامر' => $user->adventurer?->location,
        'ملف المنظّم' => $user->activityOrganizer ? '#'.$user->activityOrganizer->id : 'غير موجود',
        'نوع المنظّم' => $user->activityOrganizer?->organizer_type,
        'حالة اعتماد المنظّم' => $user->activityOrganizer?->approval_status,
    ]">
        <p class="text-sm text-muted">تعديل بيانات الحساب وكلمة المرور متاحان لصاحب الحساب من صفحة الملف الشخصي. حذف الحساب وتعطيله غير متاحين للحفاظ على السجلات المرتبطة.</p>
    </x-admin.record-details>
@endsection
