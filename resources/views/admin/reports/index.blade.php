@extends('layouts.dashboard')

@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة تحكم الإدارة')
@section('dashboard-title', 'Flagged Reviews / Fraud Reports — البلاغات والتقييمات المشبوهة')

@section('content')
    @if (session('status'))
        <div class="mb-6 rounded-control border border-emerald-500/30 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-control border border-rose-400/30 bg-rose-50 px-4 py-3 text-sm text-rose-800">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- إحصائيات سريعة --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-black/10 bg-white p-5 shadow-sm">
            <p class="text-xs text-muted">إجمالي البلاغات في الصفحة</p>
            <p class="mt-2 text-2xl font-bold text-ink">{{ $flaggedReviews->total() }}</p>
        </div>
        <div class="rounded-2xl border border-black/10 bg-white p-5 shadow-sm">
            <p class="text-xs text-muted">بانتظار المراجعة (Pending)</p>
            <p class="mt-2 text-2xl font-bold text-amber-600">
                {{ $flaggedReviews->getCollection()->where('report_status', 'pending')->count() }}
            </p>
        </div>
        <div class="rounded-2xl border border-black/10 bg-white p-5 shadow-sm">
            <p class="text-xs text-muted">مغامرات تم إيقافها (Suspended)</p>
            <p class="mt-2 text-2xl font-bold text-rose-600">
                {{ $flaggedReviews->getCollection()->filter(fn($r) => ($r->adventure?->status ?? '') === 'Suspended')->count() }}
            </p>
        </div>
    </div>

    {{-- فلاتر البحث --}}
    <form method="GET" action="{{ route('admin.reports.index') }}" class="mb-6 flex flex-wrap items-end gap-3">
        <div class="min-w-48 flex-1">
            <label for="search" class="mb-1 block text-xs font-medium text-muted">بحث (عنوان المغامرة، المغامر، السبب)</label>
            <input id="search" name="search" type="text" value="{{ $filters['search'] ?? '' }}"
                   maxlength="100"
                   class="w-full rounded-control border border-black/15 bg-white px-3 py-2 text-sm text-ink focus:outline-brand" />
        </div>
        <div>
            <label for="report_status" class="mb-1 block text-xs font-medium text-muted">حالة البلاغ</label>
            <select id="report_status" name="report_status"
                    class="rounded-control border border-black/15 bg-white px-3 py-2 text-sm text-ink focus:outline-brand">
                <option value="">— الكل —</option>
                @foreach ($reportStatuses as $val => $label)
                    <option value="{{ $val }}" @selected(($filters['report_status'] ?? '') === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit"
                class="rounded-control bg-brand px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
            تصفية
        </button>
        @if (array_filter($filters))
            <a href="{{ route('admin.reports.index') }}"
               class="rounded-control border border-black/15 bg-white px-4 py-2 text-sm text-ink hover:bg-canvas">
                مسح الفلاتر
            </a>
        @endif
    </form>

    {{-- جدول البلاغات --}}
    <div class="overflow-hidden rounded-2xl border border-black/10 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-start text-sm">
                <thead class="border-b border-black/10 bg-canvas text-xs text-muted">
                    <tr>
                        <th class="px-5 py-3.5 text-start">المغامرة وحالتها</th>
                        <th class="px-5 py-3.5 text-start">المغامر / التقييم</th>
                        <th class="px-5 py-3.5 text-start">سبب البلاغ</th>
                        <th class="px-5 py-3.5 text-start">حالة البلاغ</th>
                        <th class="px-5 py-3.5 text-end">إجراءات الإدارة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/10">
                    @forelse ($flaggedReviews as $item)
                        <tr class="hover:bg-canvas/60">
                            <td class="px-5 py-4">
                                <div class="font-bold text-ink">
                                    {{ $item->adventure?->title ?? 'مغامرة غير متوفرة' }}
                                </div>
                                <div class="mt-1">
                                    @if (($item->adventure?->status ?? '') === 'Suspended')
                                        <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-700">
                                            موقوفة (Suspended)
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">
                                            نشطة (Active)
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <div class="font-medium text-ink">
                                    {{ $item->adventurer?->user?->name ?? 'مغامر #' . $item->adventurer_id }}
                                </div>
                                <div class="text-xs text-amber-500">
                                    {{ str_repeat('★', $item->rating) }} ({{ $item->rating }}/5)
                                </div>
                                <p class="mt-1 max-w-xs truncate text-xs text-muted" title="{{ $item->comment }}">
                                    "{{ $item->comment }}"
                                </p>
                            </td>

                            <td class="px-5 py-4 text-ink">
                                {{ $item->report_reason ?? 'لم يُذكر سبب' }}
                            </td>

                            <td class="px-5 py-4">
                                @php
                                    $statusBadge = match ($item->report_status) {
                                        'resolved'  => 'bg-emerald-100 text-emerald-800',
                                        'dismissed' => 'bg-gray-100 text-gray-700',
                                        default     => 'bg-amber-100 text-amber-800',
                                    };
                                @endphp
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusBadge }}">
                                    {{ $item->report_status ?? 'pending' }}
                                </span>
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex flex-col items-end gap-2">
                                    @if (($item->adventure?->status ?? '') !== 'Suspended')
                                        <form method="POST" action="{{ route('admin.reports.suspend', $item) }}"
                                              onsubmit="return confirm('هل أنت متأكد من إيقاف هذه المغامرة المشبوهة؟')">
                                            @csrf
                                            @method('PATCH')
                                            <div class="mt-1">
                                                <label class="block text-xs text-muted mb-0.5">كلمة مرور المسؤول</label>
                                                <input type="password" name="current_password" autocomplete="current-password" required
                                                       class="w-44 rounded-control border border-black/15 px-2 py-1 text-xs focus:outline-brand"
                                                       placeholder="كلمة المرور" />
                                            </div>
                                            <button type="submit"
                                                    class="mt-1 rounded-control bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">
                                                إيقاف المغامرة (Suspend)
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.reports.dismiss', $item) }}"
                                          onsubmit="return confirm('هل أنت متأكد من تجاهل هذا البلاغ؟')">
                                        @csrf
                                        @method('PATCH')
                                        <div class="mt-1">
                                            <label class="block text-xs text-muted mb-0.5">كلمة مرور المسؤول</label>
                                            <input type="password" name="current_password" autocomplete="current-password" required
                                                   class="w-44 rounded-control border border-black/15 px-2 py-1 text-xs focus:outline-brand"
                                                   placeholder="كلمة المرور" />
                                        </div>
                                        <button type="submit"
                                                class="mt-1 rounded-control border border-black/15 bg-white px-3 py-1.5 text-xs font-medium text-ink hover:bg-canvas">
                                            تجاهل البلاغ
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-muted">
                                لا توجد أي تقييمات مُبلّغ عنها أو بلاغات احتيال حالياً.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ترقيم الصفحات --}}
    @if ($flaggedReviews->hasPages())
        <div class="mt-6">
            {{ $flaggedReviews->links() }}
        </div>
    @endif
@endsection
