@extends('layouts.dashboard')

@section('title', 'إدارة المجتمعات | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', 'إدارة المجتمعات')

@section('content')
    <div class="mb-6 flex justify-end">
        <a href="{{ route('admin.communities.create') }}"
            class="inline-flex min-h-12 items-center justify-center gap-2 rounded-control bg-brand px-6 py-3 text-base font-semibold text-white transition-colors hover:bg-brand-dark focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">
            <span aria-hidden="true">+</span>
            إنشاء مجتمع
        </a>
    </div>
    <x-admin.record-index index-route="admin.communities.index"
        :records="$records" :filters="$filters" search-label="اسم المجتمع أو رابطه أو المنطقة أو النشاط"
        :selects="[]" :headings="['المجتمع', 'المنطقة والنشاط', 'الأعضاء', 'الرسائل', 'التفاصيل']">
        @foreach ($records as $record)
            <tr>
                <td>
                    <p class="font-semibold">{{ $record->name }}</p>
                    <p class="text-muted"><bdi>{{ $record->slug }}</bdi></p>
                </td>
                <td>
                    <p>{{ $record->region }}</p>
                    <p class="text-muted">{{ $record->activity_style }}</p>
                </td>
                <td>{{ $record->memberships_count }}</td>
                <td>{{ $record->messages_count }}</td>
                <td>
                    <x-admin.action-link :href="route('admin.communities.show', $record)">التفاصيل</x-admin.action-link>
                    <x-admin.action-link :href="route('admin.communities.edit', $record)">تعديل بيانات المجتمع</x-admin.action-link>
                </td>
            </tr>
        @endforeach
    </x-admin.record-index>
@endsection
