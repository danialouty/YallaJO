@extends('layouts.dashboard')

@section('title', ('تعديل التقييم') . ' | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', 'تعديل التقييم')

@section('content')
    <x-admin.record-form :action="route('admin.reviews.update', $review)"
        :cancel="route('admin.reviews.show', $review)" method="PATCH">
        <x-admin.form-select name="rating" label="الدرجة من ٥" :options="[1 => '١', 2 => '٢', 3 => '٣', 4 => '٤', 5 => '٥']" :value="$review->rating" required />
        <x-admin.form-input name="comment" label="التعليق" :value="$review->comment" maxlength="1000" multiline required />
    </x-admin.record-form>
@endsection
