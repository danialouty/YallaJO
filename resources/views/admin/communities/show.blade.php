@extends('layouts.dashboard')

@section('title', 'تفاصيل المجتمع | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', 'تفاصيل المجتمع')

@section('content')
    <x-admin.record-details back-route="admin.communities.index" back-label="العودة إلى المجتمعات"
        :title="$community->name" :fields="[
            'الرابط' => $community->slug,
            'الوصف' => $community->description,
            'المنطقة' => $community->region,
            'نوع النشاط' => $community->activity_style,
            'صورة الغلاف' => $community->cover_image,
            'عدد الأعضاء' => $community->memberships_count,
            'عدد الرسائل (دون عرض محتواها)' => $community->messages_count,
        ]">
        <x-admin.action-link :href="route('admin.communities.edit', $community)">تعديل بيانات المجتمع</x-admin.action-link>
        <x-admin.confirm-action :action="route('admin.communities.destroy', $community)" method="DELETE" label="حذف المجتمع"
            warning="يمكن حذف المجتمع فقط إذا لم يحتوِ على رسائل. لن تُحذف الرسائل أو تُعدّل." confirmation />
    </x-admin.record-details>

    <section class="mt-8 space-y-4" aria-labelledby="community-messages-heading">
        <div class="flex flex-wrap items-baseline justify-between gap-2">
            <h2 id="community-messages-heading" class="text-xl font-bold">رسائل المجتمع</h2>
            <p class="text-sm text-muted">{{ $community->messages_count }} رسالة — للعرض فقط</p>
        </div>

        <x-ui.card padding="none">
            @if ($messages->isEmpty())
                <p class="p-6 text-muted">لا توجد رسائل في هذا المجتمع.</p>
            @else
                <div class="overflow-x-auto rounded-card">
                    <table class="w-full text-start text-sm">
                        <caption class="sr-only">رسائل المجتمع ومرسلوها</caption>
                        <thead class="bg-canvas text-ink">
                            <tr>
                                <th scope="col" class="whitespace-nowrap px-4 py-3 text-start">المرسل</th>
                                <th scope="col" class="whitespace-nowrap px-4 py-3 text-start">الرسالة</th>
                                <th scope="col" class="whitespace-nowrap px-4 py-3 text-start">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline/40">
                            @foreach ($messages as $message)
                                <tr>
                                    <td class="px-4 py-3">
                                        <p class="font-semibold">{{ $message->user?->name ?? 'مستخدم غير متاح' }}</p>
                                        <p class="text-muted"><bdi>{{ $message->user?->email ?? 'حساب محذوف' }}</bdi></p>
                                    </td>
                                    <td class="max-w-2xl whitespace-pre-wrap px-4 py-3 [overflow-wrap:anywhere]">{{ $message->body }}</td>
                                    <td class="whitespace-nowrap px-4 py-3">
                                        @if ($message->created_at)
                                            <time datetime="{{ $message->created_at->toIso8601String() }}" dir="ltr">
                                                {{ $message->created_at->format('Y-m-d H:i') }}
                                            </time>
                                        @else
                                            غير مسجّل
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-ui.card>

        {{ $messages->links() }}
    </section>
@endsection
