@extends('layouts.dashboard')

@section('title', ($organizer ? 'تعديل ملف المنظّم' : 'إضافة ملف المنظّم') . ' | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', $organizer ? 'تعديل ملف المنظّم' : 'إضافة ملف المنظّم')

@section('content')
    <x-admin.record-form :action="$organizer ? route('admin.organizers.profile.update', $organizer) : route('admin.organizers.store')"
        :cancel="$organizer ? route('admin.organizers.show', $organizer) : route('admin.organizers.index')" :method="$organizer ? 'PATCH' : 'POST'">
        @if (!$organizer)
            <x-admin.form-input name="user_id" label="رقم مستخدم بدور منظّم" type="number" min="1" required />
            <x-admin.action-link :href="route('admin.users.index', ['role' => 'Activity_Organizer'])">عرض حسابات المنظّمين</x-admin.action-link>
        @endif
        <x-admin.form-select name="organizer_type" label="النوع" :options="$types" :value="$organizer?->organizer_type" required />
        <x-admin.form-input name="license_number" label="رقم الترخيص (مطلوب للمكتب السياحي)" :value="$organizer?->license_number" maxlength="100" />
        <x-admin.form-input name="social_handle_url" label="رابط المنظّم (اختياري)" type="url" dir="ltr" :value="$organizer?->social_handle_url" maxlength="255" />
    </x-admin.record-form>
@endsection
