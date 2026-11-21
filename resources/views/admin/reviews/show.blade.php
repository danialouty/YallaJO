@extends('layouts.dashboard')

@section('title', 'تفاصيل التقييم | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', 'تفاصيل التقييم')

@section('content')
    <x-admin.record-details back-route="admin.reviews.index" back-label="العودة إلى التقييمات" :title="'التقييم رقم '.$review->id" :fields="[
        'المغامر' => $review->adventurer?->user?->name,
        'المغامرة' => $review->adventure?->title,
        'الدرجة' => $review->rating.' من ٥',
        'تاريخ التقييم' => $review->created_at?->toDateString(),
        'التعليق' => $review->comment,
    ]">
        <x-admin.action-link :href="route('admin.reviews.edit', $review)">تعديل التقييم</x-admin.action-link>
        <x-admin.confirm-action :action="route('admin.reviews.destroy', $review)" method="DELETE" label="حذف التقييم"
            warning="سيُحذف هذا التقييم، ويبقى الحجز والمغامرة وسجلات الدفع محفوظة." confirmation />
    </x-admin.record-details>
@endsection
