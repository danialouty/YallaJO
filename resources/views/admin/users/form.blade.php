@extends('layouts.dashboard')

@section('title', 'إضافة المستخدم | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', 'إضافة المستخدم')

@section('content')
    <x-admin.record-form :action="route('admin.users.store')" :cancel="route('admin.users.index')" method="POST">
        <x-admin.form-input name="name" label="الاسم" maxlength="255" required />
        <x-admin.form-input name="email" label="البريد الإلكتروني" type="email" dir="ltr" maxlength="255" required />
        <x-admin.form-input name="phone_number" label="الهاتف" type="tel" dir="ltr" maxlength="20" required />
        <x-admin.form-input name="password" label="كلمة المرور الأولية" type="password" autocomplete="new-password" required />
        <x-admin.form-input name="password_confirmation" label="تأكيد كلمة المرور" type="password" autocomplete="new-password" required />
        <x-admin.form-select name="role" label="الدور" :options="['Adventurer' => 'مغامر', 'Activity_Organizer' => 'منظّم', 'Admin' => 'مسؤول النظام']" value="Adventurer" required />
        <fieldset class="space-y-3">
            <legend class="font-semibold">بيانات مطلوبة عند إنشاء حساب منظّم</legend>
            <x-admin.form-select name="organizer_type" label="نوع المنظّم" :options="['Tourism_office' => 'مكتب سياحة', 'Adventure_group' => 'مجموعة مغامرات']" />
            <x-admin.form-input name="license_number" label="رقم الترخيص للمكتب السياحي" maxlength="100" />
        </fieldset>
    </x-admin.record-form>
@endsection
