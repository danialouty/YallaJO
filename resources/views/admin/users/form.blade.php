@extends('layouts.dashboard')

@section('title', ($user ? 'تعديل المستخدم' : 'إضافة المستخدم') . ' | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', $user ? 'تعديل المستخدم' : 'إضافة المستخدم')

@section('content')
    <x-admin.record-form :action="$user ? route('admin.users.update', $user) : route('admin.users.store')"
        :cancel="$user ? route('admin.users.show', $user) : route('admin.users.index')" :method="$user ? 'PATCH' : 'POST'">
        <x-admin.form-input name="name" label="الاسم" :value="$user?->name" maxlength="255" required />
        <x-admin.form-input name="email" label="البريد الإلكتروني" type="email" dir="ltr" :value="$user?->email" maxlength="255" required />
        <x-admin.form-input name="phone_number" label="الهاتف" type="tel" dir="ltr" :value="$user?->phone_number" maxlength="20" required />
        <x-admin.form-input name="password" :label="$user ? 'كلمة مرور جديدة (اختياري)' : 'كلمة المرور'" type="password" autocomplete="new-password" :required="!$user" />
        <x-admin.form-input name="password_confirmation" label="تأكيد كلمة المرور" type="password" autocomplete="new-password" :required="!$user" />
        @if (!$user)
            <x-admin.form-select name="role" label="الدور" :options="['Adventurer' => 'مغامر', 'Activity_Organizer' => 'منظّم', 'Admin' => 'مسؤول النظام']" value="Adventurer" required />
            <fieldset class="space-y-3">
                <legend class="font-semibold">بيانات مطلوبة عند إنشاء حساب منظّم</legend>
                <x-admin.form-select name="organizer_type" label="نوع المنظّم" :options="['Tourism_office' => 'مكتب سياحة', 'Adventure_group' => 'مجموعة مغامرات']" />
                <x-admin.form-input name="license_number" label="رقم الترخيص للمكتب السياحي" maxlength="100" />
            </fieldset>
        @else
            <p class="text-sm text-muted">الدور الحالي: <bdi>{{ $user->role }}</bdi>. تعديل الدور غير متاح.</p>
        @endif
    </x-admin.record-form>
@endsection
