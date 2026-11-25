@extends('layouts.dashboard')
@section('title', 'إدارة المنظّمين | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', 'إدارة المنظّمين')

@section('content')
    <div class="space-y-6 pb-8">
        <x-ui.card padding="lg">
            <form action="{{ route('admin.organizers.index') }}" method="GET" class="grid gap-4 sm:grid-cols-2">
                <x-ui.input name="search" id="organizer-search" label="البحث عن منظّم"
                    hint="ابحث بالاسم أو البريد الإلكتروني أو الهاتف أو رقم الترخيص."
                    maxlength="100" :value="$filters['search'] ?? null" />
                <div class="space-y-2">
                    <label for="organizer-status" class="block text-sm font-semibold">حالة الاعتماد</label>
                    <select id="organizer-status" name="status"
                        @error('status') aria-invalid="true" aria-describedby="organizer-filter-error" @enderror
                        class="block w-full rounded-control border border-outline bg-surface py-3 ps-4 pe-10 text-sm rtl:bg-position-[left_0.5rem_center] focus:border-brand focus:ring-brand">
                        <option value="">كل الحالات</option>
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['status'] ?? null) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <p id="organizer-filter-error" role="alert" class="text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-wrap items-center gap-4 sm:col-span-2">
                    <x-ui.button type="submit">تطبيق الفلاتر</x-ui.button>
                    <a href="{{ route('admin.organizers.index') }}" class="rounded-control text-sm font-semibold text-brand underline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand">مسح الفلاتر</a>
                </div>
            </form>
        </x-ui.card>

        <x-ui.card padding="none">
            @if ($organizers->isEmpty())
                <p class="p-6 text-muted">لا توجد سجلات منظّمين مطابقة في هذه الصفحة.</p>
            @else
                <div class="overflow-x-auto rounded-card">
                    <table class="w-full text-sm">
                        <caption class="sr-only">سجلات المنظّمين وحالات الاعتماد</caption>
                        <thead class="bg-canvas text-ink">
                            <tr>
                                @foreach (['حساب المنظّم', 'النوع والترخيص', 'الحالة', 'آخر اعتماد', 'التفاصيل'] as $heading)
                                    <th scope="col" class="whitespace-nowrap px-4 py-3 text-start">{{ $heading }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline/40">
                            @foreach ($organizers as $organizer)
                                <tr>
                                    <th scope="row" class="px-4 py-3 text-start font-normal">
                                        <p class="font-semibold"><bdi>{{ $organizer->user?->name ?? 'حساب غير متاح' }}</bdi></p>
                                        @if ($organizer->user)
                                            <p class="text-muted"><bdi>{{ $organizer->user->email }}</bdi></p>
                                            <p class="text-muted"><bdi>{{ $organizer->user->phone_number }}</bdi></p>
                                        @endif
                                    </th>
                                    <td class="px-4 py-3">
                                        <p>{{ $types[$organizer->organizer_type] ?? $organizer->organizer_type }}</p>
                                        <p class="text-muted"><bdi>{{ $organizer->license_number ?: 'بدون رقم ترخيص' }}</bdi></p>
                                        @if (preg_match('/\Ahttps?:\/\//i', $organizer->social_handle_url ?? ''))
                                            <a href="{{ $organizer->social_handle_url }}" class="text-brand underline focus-visible:outline-2 focus-visible:outline-brand">رابط المنظّم</a>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3">
                                        <span class="rounded-full px-2 py-1 font-semibold {{ $organizer->approval_status === 'Approved' ? 'bg-forest/10 text-forest' : ($organizer->approval_status === 'Rejected' || $organizer->approval_status === 'Banned' ? 'bg-danger/10 text-danger' : 'bg-brand/10 text-brand') }}">{{ $statuses[$organizer->approval_status] ?? $organizer->approval_status }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3">
                                        @if ($organizer->approved_at)
                                            <time datetime="{{ $organizer->approved_at->toIso8601String() }}" dir="ltr">{{ $organizer->approved_at->format('Y-m-d H:i') }}</time>
                                        @endif
                                        <p class="text-muted"><bdi>{{ $organizer->approver?->name ?? ($organizer->approved_at ? 'المعتمِد غير متاح' : 'غير مسجّل') }}</bdi></p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <x-admin.action-link :href="route('admin.organizers.show', $organizer)">التفاصيل</x-admin.action-link>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-ui.card>

        {{ $organizers->links() }}
    </div>
@endsection
