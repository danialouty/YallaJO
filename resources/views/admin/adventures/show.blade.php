@extends('layouts.dashboard')

@section('title', 'تفاصيل المغامرة | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', 'تفاصيل المغامرة')

@section('content')
    <x-admin.record-details back-route="admin.adventures.index" back-label="العودة إلى المغامرات" :title="$adventure->title" :fields="[
        'رقم المغامرة' => $adventure->id,
        'المنظّم' => $adventure->activityOrganizer?->user?->name,
        'الحالة' => ['Active' => 'نشطة', 'Suspended' => 'موقوفة'][$adventure->status] ?? $adventure->status,
        'الصعوبة' => ['Easy' => 'سهلة', 'Medium' => 'متوسطة', 'Hard' => 'صعبة'][$adventure->difficulty_level] ?? $adventure->difficulty_level,
        'التصنيف' => $adventure->category,
        'الموقع' => $adventure->location,
        'السعة' => $adventure->capacity,
        'المدة بالساعات' => $adventure->duration_hours,
        'السعر بالدينار الأردني' => $adventure->price,
        'تاريخ الإنشاء' => $adventure->created_at?->toDateString(),
        'الوصف' => $adventure->description,
        'المتطلبات' => $adventure->requirements,
    ]">
        <x-admin.action-link :href="route('admin.adventures.edit', $adventure)">تعديل المغامرة</x-admin.action-link>
        @if ($adventure->status !== 'Suspended')
            <x-admin.confirm-action :action="route('admin.adventures.suspend', $adventure)" method="PATCH" label="تعليق المغامرة"
                warning="ستُستبعد المغامرة من قوائم المغامرات النشطة، وتبقى الحجوزات والسجلات المرتبطة محفوظة." />
        @endif
    </x-admin.record-details>
@endsection
