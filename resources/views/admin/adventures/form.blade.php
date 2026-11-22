@extends('layouts.dashboard')

@section('title', ($adventure ? 'تعديل المغامرة' : 'إضافة المغامرة') . ' | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', $adventure ? 'تعديل المغامرة' : 'إضافة المغامرة')

@section('content')
    <x-admin.record-form :action="$adventure ? route('admin.adventures.update', $adventure) : route('admin.adventures.store')"
        :cancel="$adventure ? route('admin.adventures.show', $adventure) : route('admin.adventures.index')" :method="$adventure ? 'PATCH' : 'POST'">
        @if (!$adventure)
            <x-admin.form-input name="activity_organizer_id" label="رقم ملف المنظّم" type="number" min="1" required />
            <x-admin.action-link :href="route('admin.organizers.index')">عرض سجلات المنظّمين</x-admin.action-link>
        @endif
        <x-admin.form-input name="title" label="العنوان" :value="$adventure?->title" maxlength="255" required />
        <x-admin.form-input name="description" label="الوصف" :value="$adventure?->description" maxlength="255" multiline required />
        <x-admin.form-input name="location" label="الموقع" :value="$adventure?->location" maxlength="255" required />
        <x-admin.form-input name="requirements" label="المتطلبات" :value="$adventure?->requirements" maxlength="10000" multiline required />
        <x-admin.form-select name="category" label="التصنيف" :value="$adventure?->category" :options="array_combine($categories, $categories)" required />
        <x-admin.form-select name="difficulty_level" label="الصعوبة" :value="$adventure?->difficulty_level ?? 'Easy'" :options="['Easy' => 'سهلة', 'Medium' => 'متوسطة', 'Hard' => 'صعبة']" required />
        <x-admin.form-select name="status" label="الحالة" :value="$adventure?->status ?? 'Active'" :options="['Active' => 'نشطة', 'Suspended' => 'معلّقة']" required />
        <x-admin.form-input name="capacity" label="السعة" type="number" :value="$adventure?->capacity" min="1" required />
        <x-admin.form-input name="duration_hours" label="المدة بالساعات" type="number" :value="$adventure?->duration_hours" min="0.01" max="999.99" step="0.01" required />
        <x-admin.form-input name="price" label="السعر بالدينار الأردني" type="number" :value="$adventure?->price" min="0" max="9999999.99" step="0.01" required />
    </x-admin.record-form>
@endsection
