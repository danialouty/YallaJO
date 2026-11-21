@extends('layouts.dashboard')

@section('title', ('تعديل الحجز') . ' | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', 'تعديل الحجز')

@section('content')
    <x-admin.record-form :action="route('admin.bookings.update', $booking)"
        :cancel="route('admin.bookings.show', $booking)" method="PATCH">
        <p class="text-sm text-muted">يُعاد حساب الإجمالي من سعر المقعد المسجّل في الحجز.</p>
        <x-admin.form-input name="participants_count" label="عدد المشاركين" type="number" :value="$booking->participants_count" min="1" required />
    </x-admin.record-form>
@endsection
