@extends('layouts.dashboard')

@section('title', 'المغامرات | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', 'المغامرات')

@section('content')
    <x-admin.record-index index-route="admin.adventures.index" create-route="admin.adventures.create" :records="$records" :filters="$filters"
        search-label="عنوان المغامرة" :selects="[
        'status' => ['الحالة', ['Active' => 'نشطة', 'Suspended' => 'موقوفة']],
        'difficulty_level' => ['الصعوبة', ['Easy' => 'سهلة', 'Medium' => 'متوسطة', 'Hard' => 'صعبة']],
    ]"
        :headings="['المغامرة', 'المنظّم', 'الحالة', 'الصعوبة', 'السعر', 'التفاصيل']">
        @foreach ($records as $record)
            <tr>
                <td class="[overflow-wrap:anywhere]"><bdi>{{ $record->title }}</bdi></td>
                <td class="[overflow-wrap:anywhere]">{{ $record->activityOrganizer?->user?->name ?? 'غير متاح' }}</td>
                <td class="[overflow-wrap:anywhere]">{{ ['Active' => 'نشطة', 'Suspended' => 'موقوفة'][$record->status] ?? $record->status }}</td>
                <td class="[overflow-wrap:anywhere]">{{ ['Easy' => 'سهلة', 'Medium' => 'متوسطة', 'Hard' => 'صعبة'][$record->difficulty_level] ?? $record->difficulty_level }}</td>
                <td class="[overflow-wrap:anywhere]"><bdi>{{ $record->price }}</bdi> د.أ</td>
                <td>
                    <x-admin.action-link :href="route('admin.adventures.edit', $record)">تعديل</x-admin.action-link>
                    <a href="{{ route('admin.adventures.show', $record) }}" class="inline-flex min-h-11 items-center rounded-control text-brand underline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand">
                        عرض<span class="sr-only"> السجل رقم {{ $record->id }}</span>
                    </a>
                </td>
            </tr>
        @endforeach
    </x-admin.record-index>
@endsection
