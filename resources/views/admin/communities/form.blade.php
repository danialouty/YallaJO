@extends('layouts.dashboard')

@section('title', ($community ? 'تعديل المجتمع' : 'إنشاء مجتمع') . ' | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', $community ? 'تعديل بيانات المجتمع' : 'إنشاء مجتمع')

@section('content')
    <x-admin.record-form :action="$community ? route('admin.communities.update', $community) : route('admin.communities.store')"
        :cancel="$community ? route('admin.communities.show', $community) : route('admin.communities.index')"
        :method="$community ? 'PATCH' : 'POST'">
        <x-admin.form-input name="name" label="اسم المجتمع" :value="$community?->name" maxlength="255" required />
        <x-admin.form-input name="slug" label="رابط المجتمع (Slug)" :value="$community?->slug" maxlength="255" required />
        <x-admin.form-input name="description" label="الوصف" :value="$community?->description" maxlength="10000" multiline required />
        <x-admin.form-input name="region" label="المنطقة" :value="$community?->region" maxlength="255" required />
        <x-admin.form-input name="activity_style" label="نوع النشاط" :value="$community?->activity_style" maxlength="255" required />
        <x-admin.form-input name="cover_image" label="رابط صورة الغلاف (اختياري)" type="url" dir="ltr" :value="$community?->cover_image" maxlength="255" />
        <p class="text-sm text-muted">تعديل بيانات المجتمع لا يغيّر رسائله أو محتواها.</p>
    </x-admin.record-form>
@endsection
