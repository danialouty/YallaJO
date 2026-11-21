@extends('layouts.dashboard')

@section('title', 'المستخدمون | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', 'المستخدمون')

@section('content')
    <x-admin.record-index index-route="admin.users.index" create-route="admin.users.create" :records="$records" :filters="$filters"
        search-label="الاسم أو البريد الإلكتروني" :selects="['role' => ['الدور', ['Admin' => 'مسؤول النظام', 'Adventurer' => 'مغامر', 'Activity_Organizer' => 'منظّم']]]"
        :headings="['المستخدم', 'البريد الإلكتروني', 'الدور', 'تاريخ التسجيل', 'التفاصيل']">
        @foreach ($records as $record)
            <tr>
                <td class="[overflow-wrap:anywhere]"><bdi>{{ $record->name }}</bdi></td>
                <td class="[overflow-wrap:anywhere]"><bdi>{{ $record->email }}</bdi></td>
                <td class="[overflow-wrap:anywhere]">{{ ['Admin' => 'مسؤول النظام', 'Adventurer' => 'مغامر', 'Activity_Organizer' => 'منظّم'][$record->role] ?? $record->role }}</td>
                <td class="[overflow-wrap:anywhere]">{{ $record->created_at?->toDateString() ?? 'غير مسجّل' }}</td>
                <td>
                    <x-admin.action-link :href="route('admin.users.edit', $record)">تعديل</x-admin.action-link>
                    <a href="{{ route('admin.users.show', $record) }}" class="inline-flex min-h-11 items-center rounded-control text-brand underline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand">
                        عرض<span class="sr-only"> السجل رقم {{ $record->id }}</span>
                    </a>
                </td>
            </tr>
        @endforeach
    </x-admin.record-index>
@endsection
